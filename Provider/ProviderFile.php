<?php 
namespace App\Provider;

use Seriti\Tools\Upload;

class ProviderFile extends Upload 
{
  
    public function setup($param = []) 
    {
        $id_prefix = 'SRV';

        $param = ['row_name'=>'Provider document',
                  'pop_up'=>true,
                  'update_calling_page'=>true,
                  'prefix'=>'',//will prefix file_name if used, but file_id.ext is unique 
                  'upload_location'=>$id_prefix];  
        parent::setup($param);

        if($this->user_access_level !== 'GOD' and $this->user_access_level !== 'ADMIN') {
            $access['email'] = false;
            $this->modifyAccess($access);
        }
        
        $param=[];
        $param['table']     = TABLE_PREFIX.'provider';
        $param['key']       = 'provider_id';
        $param['label']     = 'name';
        $param['child_col'] = 'location_id';
        $param['child_prefix'] = $id_prefix;
        $param['show_sql'] = 'SELECT CONCAT("Service Provider: ",name) FROM '.TABLE_PREFIX.'provider WHERE provider_id = {KEY_VAL}';
        $this->setupMaster($param);

        $this->addAction('check_box');
        $this->addAction('edit');
        $this->addAction(['type'=>'delete','text'=>'delete','pos'=>'R']);
        
    }
}

