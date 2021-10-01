<?php 
namespace App\Provider;

use Seriti\Tools\Upload;

class ExpenseFile extends Upload 
{
  
    public function setup($param = []) 
    {
        $id_prefix = 'EXP';

        $param = ['row_name'=>'Expense document',
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
        $param['table']     = TABLE_PREFIX.'expense';
        $param['key']       = 'expense_id';
        $param['label']     = 'amount';
        $param['child_col'] = 'location_id';
        $param['child_prefix'] = $id_prefix;
        $param['show_sql'] = 'SELECT CONCAT("Expense: ",amount," on ",date) FROM '.TABLE_PREFIX.'expense WHERE expense_id = {KEY_VAL}';
        $this->setupMaster($param);

        $this->addAction('check_box');
        $this->addAction('edit');
        $this->addAction(['type'=>'delete','text'=>'delete','pos'=>'R']);
        
    }
}

