<?php 
namespace App\Provider;

use Exception;
use Seriti\Tools\Table;
use Seriti\Tools\Html;

class TaskDiary extends Table 
{
    //configure
    public function setup() 
    {
        $param = ['row_name'=>'Diary entry','row_name_plural'=>'Diary entries','col_label'=>'subject','pop_up'=>true];
        parent::setup($param);
                        
        //NB: specify master table relationship
        $this->setupMaster(array('table'=>TABLE_PREFIX.'task','key'=>'task_id','child_col'=>'task_id', 
                                 'show_sql'=>'SELECT CONCAT("Task: ",name) FROM '.TABLE_PREFIX.'task WHERE task_id = "{KEY_VAL}" '));                        

        $this->addTableCol(array('id'=>'diary_id','type'=>'INTEGER','title'=>'Diary ID','key'=>true,'key_auto'=>true,'list'=>false));
        $this->addTableCol(array('id'=>'subject','type'=>'STRING','title'=>'Subject'));
        $this->addTableCol(array('id'=>'notes','type'=>'TEXT','title'=>'Notes'));
        $this->addTableCol(array('id'=>'date','type'=>'DATETIME','title'=>'Note date','new'=>date('Y-m-d j:i')));

        $this->addSortOrder('T.date, T.diary_id ','Entry date','DEFAULT');

        $this->addAction(array('type'=>'edit','text'=>'edit'));
        $this->addAction(array('type'=>'delete','text'=>'delete','pos'=>'R'));

        $this->addSearch(array('notes','date'),array('rows'=>1));
    }    
}
