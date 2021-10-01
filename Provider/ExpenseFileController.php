<?php
namespace App\Provider;

use Psr\Container\ContainerInterface;
use App\Provider\ExpenseFile;

class ExpenseFileController
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $args)
    {
        $table = TABLE_PREFIX.'file'; 
        $upload = new ExpenseFile($this->container->mysql,$this->container,$table);

        $upload->setup();
        $html = $upload->processUpload();
        
        $template['html'] = $html;
        //$template['title'] = MODULE_LOGO.'All transfer Files';
        
        return $this->container->view->render($response,'admin_popup.php',$template);
    }
}