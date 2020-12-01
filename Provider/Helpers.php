<?php
namespace App\Provider;

use Exception;
use Seriti\Tools\Calc;
use Seriti\Tools\Csv;
use Seriti\Tools\Doc;
use Seriti\Tools\Html;
use Seriti\Tools\Pdf;
use Seriti\Tools\Date;
use Seriti\Tools\SITE_TITLE;
use Seriti\Tools\BASE_UPLOAD;
use Seriti\Tools\UPLOAD_DOCS;
use Seriti\Tools\UPLOAD_TEMP;
use Seriti\Tools\SITE_NAME;

use Psr\Container\ContainerInterface;


//static functions for client module
class Helpers {
    public static function checkTimeout($time_start,$time_max,$time_tolerance=5) {
        if ($time_start == 0 or $time_max == 0) return false;
          
        $time_passed = time()-$time_start;
        $time_trigger = $time_max-$time_tolerance;
              
        if($time_passed > $time_trigger) return true; return false;
    }

    public static function sendTaskReport($db,ContainerInterface $container,$task_id,$email,&$error_str) {
        $error_str = '';
        $error_tmp = '';

        $system = $container['system'];
        $mail = $container['mail'];
        $s3 = $container['s3'];
        
        $options = [];
        $options['format'] = 'HTML';
        $options['file_links'] = true;
        
        $sql = 'SELECT task_id,provider_id,name,description,date_create,status '.
               'FROM '.TABLE_PREFIX.'task '.
               'WHERE task_id = "'.$db->escapeSql($task_id).'" ';
        $task = $db->readSqlRecord($sql);
        
        $content = self::taskReport($db,$s3,$task_id,$options,$error_tmp);
        if($error_tmp !== '') {
            $error_str .= 'Could NOT generate report: '.$error_tmp;
        } else {
            //email configuration
            $mail_footer = $system->getDefault('EMAIL_FOOTER','');;
            $mail_from = ''; //will use config default
            $mail_to = $email;
                  
            $subject = $seriti_config['site']['title'].' Task: '.$task['name'];
            //$content includes document links
            $body = $content."\r\n\r\n";
            $body .= $mail_footer."\r\n";
             
                        
            $param = array();
            if($options['format'] === 'HTML') $param['format'] = 'html';
              
            $mail->sendEmail($mail_from,$mail_to,$subject,$body,$error_tmp,$param);
            if($error_tmp != '') { 
              $error_str .= 'Error sending task report to email['. $mail_to.']:'.$error_tmp; 
            }       
        }    
    }  
  
    public static function taskReport($db,$s3,$task_id,$options=array(),&$error_str) {
        $error_str = '';
        $error_tmp = '';
        $output = '';
        
        if(!isset($options['task_type'])) $options['task_type'] = 'Task';
        if(!isset($options['file_links'])) $options['file_links'] = true;
        if(!isset($options['format'])) $options['format'] = 'TEXT'; //or HTML
        
        $sql = 'SELECT task_id,provider_id,name,description,remarks,date_create,status '.
             'FROM '.TABLE_PREFIX.'task '.
             'WHERE task_id = "'.$db->escapeSql($task_id).'" ';
        $task = $db->readSqlRecord($sql);
        if($options['format'] === 'TEXT') {
            $output .= $options['task_type'].': '.strtoupper($task['name']).' created on '.Date::formatDate($task['date_create'])."\r\n";
            $output .= $task['description']."\r\n\r\n";
            if($task['remarks'] !== '') $output .= 'Remarks: '.$task['remarks']."\r\n\r\n";
        }
        if($options['format'] === 'HTML') {
            $output .= '<h1>'.$options['task_type'].': '.$task['name'].'</h1>'.
                       '<p><i>created on '.Date::formatDate($task['date_create']).'</i></p>';
                     
            $output .= '<p>'.nl2br($task['description']).'</p><br/>';
            if($task['remarks'] !== '') $output .= '<p>Remarks: '.nl2br($task['remarks']).'</p><br/>';
        }   
        
        $sql = 'SELECT diary_id,date,subject,notes FROM '.TABLE_PREFIX.'task_diary '.
               'WHERE task_id = "'.$db->escapeSql($task_id).'" '.
               'ORDER BY date, diary_id ';
        $diary = $db->readSqlArray($sql);
        if($diary != 0) {
            foreach($diary as $id => $entry) {
                if($options['format'] === 'TEXT') {
                    $output .= '#'.Date::formatDate($entry['date']).' - '.strtoupper($entry['subject']).':'."\r\n".
                               $entry['notes']."\r\n\r\n"; 
                }  
                if($options['format'] === 'HTML') {
                    $output .= '<h2>'.Date::formatDate($entry['date']).' - '.strtoupper($entry['subject']).':</h2>'.
                               '<p>'.nl2br($entry['notes']).'</p><br/>';  
                }     
            }
        }  
        
        
        if($options['file_links']) {
            $sql = 'SELECT file_id,file_name,file_name_orig,file_date,key_words '.
                   'FROM '.TABLE_PREFIX.'file '.
                   'WHERE location_id ="TSK'.$task_id.'" ORDER BY file_id ';
            $files = $db->readSqlArray($sql);
            
            if($files != 0) {
              
                $s3_expire = '30 days';
                $s3_expire_date = Date::formatDate(date('Y-m-d',time()+(30*24*60*60)));
                
                
                if($options['format'] === 'TEXT') {
                    $output .= "\r\n\r\n";
                    $output .= 'The following documents are available for download(links expire on '.$s3_expire_date.'):'."\r\n"; 
                }
                if($options['format'] === 'HTML') {
                    $output .= '<h2>The following documents are available for download(links expire on '.$s3_expire_date.'):</h2><ul>'; 
                }    
                
                foreach($files as $file_id => $file) {
                    $param = [];
                    $param['file_name_change'] = $file['file_name_orig'];
                    $param['expire'] = $s3_expiry;
                    $url = $s3->getS3Url($file['file_name'],$param); 
                    if($options['format'] === 'TEXT') {
                        $output .= $file['file_name_orig'].":\r\n".$url."\r\n"; 
                    }
                    if($options['format'] === 'HTML') {
                        $output .= '<li><a href="'.$url.'">'.$file['file_name_orig'].'</a></li>'; 
                    }    
                }   
                
                if($options['format'] === 'HTML') $output .= '</ul>';
            }
        }
        
        return $output;  
    }    
}
