<?php 
namespace App\Provider;

use Exception;
use Seriti\Tools\Table;
use Seriti\Tools\Validate;


class Provider extends Table 
{
    //configure
    public function setup() 
    {
        $param = ['row_name'=>'Service provider','col_label'=>'name'];
        parent::setup($param);

        $this->addForeignKey(array('table'=>'death_claim','col_id'=>'provider_id','message'=>'Death claims'));
        $this->addForeignKey(array('table'=>'funeral_claim','col_id'=>'provider_id','message'=>'Funeral claims'));

        $this->addTableCol(array('id'=>'provider_id','type'=>'INTEGER','title'=>'Provider ID','key'=>true,'key_auto'=>true,'list'=>false));
        $this->addTableCol(array('id'=>'name','type'=>'STRING','title'=>'Provider name'));
        $this->addTableCol(array('id'=>'contact_name','type'=>'STRING','title'=>'Contact person'));
        $this->addTableCol(array('id'=>'email','type'=>'EMAIL','title'=>'Email primary'));
        $this->addTableCol(array('id'=>'email_alt','type'=>'EMAIL','title'=>'Email alternative','required'=>false));
        $this->addTableCol(array('id'=>'status','type'=>'STRING','title'=>'Status','new'=>'ACTIVE'));

        $this->setupFiles(array('location'=>'SRV','max_no'=>10,'icon'=>'<img src="/images/folder.png" border="0">manage',
                                'table'=>TABLE_PREFIX.'file','list'=>true,'list_no'=>10,'storage'=>STORAGE,
                                'link_url'=>'provider_file','link_data'=>'SIMPLE','width'=>'700','height'=>'600'));


        $this->addAction(array('type'=>'edit','text'=>'edit'));
        //$this->addAction(array('type'=>'view','text'=>'view'));
        $this->addAction(array('type'=>'delete','text'=>'delete','pos'=>'R'));

        //$this->addAction(array('type'=>'popup','text'=>'invoices','url'=>'invoice_fixed.php','mode'=>'view','width'=>600,'height'=>800)); 

        $this->addSearch(array('name','contact_name','email','status'),array('rows'=>2));

        $this->addSelect('status','(SELECT "ACTIVE") UNION (SELECT "INACTIVE")');


    }
        
    
}