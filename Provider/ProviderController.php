<?php
namespace App\Provider;

use Psr\Container\ContainerInterface;
use App\Provider\Provider;

class ProviderController
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $args)
    {
        
        $table = TABLE_PREFIX.'provider';
        
        $table = new Provider($this->container->mysql,$this->container,$table);

        $table->setup();
        $html = $table->processTable();
        
        $template['html'] = $html;
        $template['title'] = 'Service Providers';
        
        return $this->container->view->render($response,'admin.php',$template);
    }
}