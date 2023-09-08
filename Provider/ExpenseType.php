<?php 
namespace App\Provider;

use Exception;
use Seriti\Tools\Table;
use Seriti\Tools\Validate;


class ExpenseType extends Table 
{
    //configure
    public function setup($param = []) 
    {
        $param = ['row_name'=>'Expense Type','col_label'=>'name'];
        parent::setup($param);

        $this->addForeignKey(array('table'=>TABLE_PREFIX.'expense','col_id'=>'type_id','message'=>'Provider expense')); 

        $this->addTableCol(['id'=>'type_id','type'=>'INTEGER','title'=>'Type ID','key'=>true,'key_auto'=>true,'list'=>false]);
        //$this->addTableCol(['id'=>'rank','type'=>'INTEGER','title'=>'Sort order','hint'=>'(This specify order in drop down list of reasons)']);
        $this->addTableCol(['id'=>'name','type'=>'STRING','title'=>'Name','hint'=>'(This will appear in drop down list of expense types)']);
        $this->addTableCol(['id'=>'description','type'=>'TEXT','title'=>'Description','required'=>false]);
        $this->addTableCol(['id'=>'status','type'=>'STRING','title'=>'Status','hint'=>'(Select HIDE to remove reason from drop down list)']);
         
        $this->addAction(['type'=>'edit','text'=>'edit','icon_text'=>'edit']);
        $this->addAction(['type'=>'delete','text'=>'delete','pos'=>'R','icon_text'=>'delete']);

        $this->addSearch(['name','description','status'],['rows'=>1]);

        $this->addSelect('status','(SELECT "OK") UNION (SELECT "HIDE")');
    }
    
    
}