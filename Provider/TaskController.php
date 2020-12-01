<?php
namespace App\Provider;

use Psr\Container\ContainerInterface;
use App\Provider\Task;

class TaskController
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $args)
    {
        
        $table = TABLE_PREFIX.'task';
        $table = new Task($this->container->mysql,$this->container,$table);

        $table->setup();
        $html = $table->processTable();
        
        $template['html'] = $html;
        $template['title'] = 'Service provider task management';
        
        return $this->container->view->render($response,'admin.php',$template);
    }
}