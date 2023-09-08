<?php 
namespace App\Provider;

use Exception;
use Seriti\Tools\Table;
use Seriti\Tools\Validate;


class Expense extends Table 
{
    //configure
    public function setup($param = []) 
    {
        $param = ['row_name'=>'Expense','col_label'=>'amount'];
        parent::setup($param);

        if($this->user_access_level !== 'GOD') {
            $access['read_only'] = true; 
            $this->modifyAccess($access);
        }

        $this->addTableCol(['id'=>'expense_id','type'=>'INTEGER','title'=>'Expense ID','key'=>true,'key_auto'=>true,'edit'=>false,'list'=>true]);
        $this->addTableCol(['id'=>'user_id','type'=>'INTEGER','title'=>'Added by','join'=>'name FROM user_admin WHERE user_id','edit'=>false]); 
        $this->addTableCol(['id'=>'provider_id','type'=>'INTEGER','title'=>'Service Provider','join'=>'name FROM '.TABLE_PREFIX.'provider WHERE provider_id']);
        $this->addTableCol(['id'=>'type_id','type'=>'INTEGER','title'=>'Expense category','join'=>'name FROM '.TABLE_PREFIX.'expense_type WHERE type_id']);
        $this->addTableCol(['id'=>'date','type'=>'DATE','title'=>'Expense date','new'=>date('Y-m-d')]);
        $this->addTableCol(['id'=>'amount','type'=>'DECIMAL','title'=>'Amount']);
        $this->addTableCol(['id'=>'comment','type'=>'TEXT','title'=>'Comments','required'=>false]);
        //$this->addTableCol(['id'=>'status','type'=>'STRING','title'=>'Status'));

        $this->addSql('WHERE','T.type = "EXPENSE"');

        $this->addSortOrder('T.date DESC','Date of expense, most recent first','DEFAULT');

        $this->addSearchAggregate(['sql'=>'SUM(T.amount)','title'=>'Total Amount']);
        
        
        $this->setupFiles(['location'=>'EXP','max_no'=>10,'icon'=>'<img src="/images/folder.png" border="0">manage',
                           'table'=>TABLE_PREFIX.'file','list'=>true,'list_no'=>10,'storage'=>STORAGE,'search'=>true,
                           'link_url'=>'expense_file','link_data'=>'SIMPLE','width'=>'700','height'=>'600']);
    

        $this->addAction(['type'=>'edit','text'=>'edit']);
        //$this->addAction(['type'=>'view','text'=>'view'));
        $this->addAction(['type'=>'delete','text'=>'delete','pos'=>'R']);

        $this->addSearch(['expense_id','user_id','provider_id','type_id','date','amount','comment'],['rows'=>2]);

        $this->addSelect('user_id','SELECT user_id,name FROM user_admin WHERE status <> "HIDE" ORDER BY name');
        $this->addSelect('provider_id','SELECT provider_id,name FROM '.TABLE_PREFIX.'provider ORDER BY name');
        $this->addSelect('type_id','SELECT type_id,name FROM '.TABLE_PREFIX.'expense_type WHERE status = "OK" ORDER BY name');
    }

    protected function afterUpdate($id,$edit_type,$form) {
        if($edit_type === 'INSERT') {
            $sql = 'UPDATE '.$this->table.' SET type = "EXPENSE", user_id =  "'.$this->db->escapeSql($this->user_id).'" '.
                   'WHERE expense_id = "'.$this->db->escapeSql($id).'"';
            $this->db->executeSql($sql,$error_str);    
        }  
    }

}