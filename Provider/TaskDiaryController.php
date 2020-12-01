<?php
namespace App\Provider;

use Psr\Container\ContainerInterface;
use App\Provider\TaskDiary;

class TaskDiaryController
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $args)
    {
        $table = TABLE_PREFIX.'task_diary'; 
        $table = new TaskDiary($this->container->mysql,$this->container,$table);

        $table->setup();
        $html = $table->processTable();
        
        $template['html'] = $html;
        //$template['title'] = MODULE_LOGO.'All transfer Files';
        
        return $this->container->view->render($response,'admin_popup.php',$template);
    }
}