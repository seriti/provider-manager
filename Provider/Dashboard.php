<?php
namespace App\Provider;

use Seriti\Tools\Dashboard AS DashboardTool;

class Dashboard extends DashboardTool
{
     

    //configure
    public function setup($param = []) 
    {
        $this->col_count = 2;  

        $login_user = $this->getContainer('user'); 

        //(block_id,col,row,title)
        $this->addBlock('ADD',1,1,'Capture new data');
        $this->addItem('ADD','Add a new Task',['link'=>"task?mode=add"]);
        $this->addItem('ADD','Add a new Service provider',['link'=>"provider?mode=add"]);
                
        if($login_user->getAccessLevel() === 'GOD') {
            $this->addBlock('CONFIG',1,2,'Module Configuration');
            $this->addItem('CONFIG','Setup Database',['link'=>'setup_data','icon'=>'setup']);
            $this->addItem('CONFIG','Manage expense types',['link'=>'expense_type','icon'=>'setup']);
        }    
        
    }

}
