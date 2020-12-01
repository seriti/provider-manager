<?php  
/*
NB: This is not stand alone code and is intended to be used within "seriti/slim3-skeleton" framework
The code snippet below is for use within an existing src/routes.php file within this framework
copy the "/provider" group into the existing "/admin" group within existing "src/routes.php" file 
*/

$app->group('/admin', function () {

    $this->group('/provider', function () {
        $this->any('/dashboard', \App\Provider\DashboardController::class);
        $this->any('/provider', \App\Provider\ProviderController::class);
        $this->any('/provider_file', \App\Provider\ProviderFileController::class);
        $this->any('/task', \App\Provider\TaskController::class);
        $this->any('/task_file', \App\Provider\TaskFileController::class);
        $this->any('/task_diary', \App\Provider\TaskDiaryController::class);
        $this->get('/setup_data', \App\Provider\SetupDataController::class);
        $this->any('/report', \App\Provider\ReportController::class);
    })->add(\App\Provider\Config::class);
        
})->add(\App\User\ConfigAdmin::class);



