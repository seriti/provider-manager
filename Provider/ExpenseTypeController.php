<?php
namespace App\Provider;

use Psr\Container\ContainerInterface;
use App\Provider\ExpenseType;

class ExpenseTypeController
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response, $args)
    {
        
        $table = TABLE_PREFIX.'expense_type';
        
        $table = new ExpenseType($this->container->mysql,$this->container,$table);

        $table->setup();
        $html = $table->processTable();
        
        $template['html'] = $html;
        $template['title'] = 'Expense types';
        
        return $this->container->view->render($response,'admin.php',$template);
    }
}