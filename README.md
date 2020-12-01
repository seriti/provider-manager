# Service provider manager module. 

## Designed for small business applications.

Simple service provider task management solution for keeping track of who said what and when.
You can create unlimited service providers and manage documentation linked to them. Then create tasks associated with a provider 
and capture task documents and maintain a diary for each task. Generate a task summary report so you can keep track of task progress. 

## Requires Seriti Slim 3 MySQL Framework skeleton

This module integrates seamlessly into [Seriti skeleton framework](https://github.com/seriti/slim3-skeleton).
You need to first install the skeleton framework and then download the source files for the module and follow these instructions.

It is possible to use this module independantly from the seriti skeleton but you will still need the [Seriti tools library](https://github.com/seriti/tools).
It is strongly recommended that you first install the seriti skeleton to see a working example of code use before using it within another application framework.
That said, if you are an experienced PHP programmer you will have no problem doing this and the required code footprint is very small.  

## Install the module

1.) Install Seriti Skeleton framework(see the framework readme for detailed instructions):   
    **composer create-project seriti/slim3-skeleton [directory-for-app]**
    Make sure that you have thsi working before you proceed.

2.) Download a copy of provider manager module source code directly from github and unzip,  
or by using **git clone https://github.com/seriti/provider-manager** from command line.
Once you have a local copy of module code check that it has following structure:

/Provider/(all module implementation classes are in this folder)  
/setup_app.php  
/routes.php  
/templates/(all templates required in this folder)  

3.) Copy the **Provider** folder and all its contents into "[directory-for-app]/app" folder.

4.) Open the routes.php file and insert the **$this->group('/provider', function (){}** route definition block
within the existing  **$app->group('/admin', function () {}** code block contained in existing skeleton **[directory-for-app]/src/routes.php** file.
5.) Open the setup_app.php file and  add the module config code snippet into bottom of skeleton **[directory-for-app]/src/setup_app.php** file.
Please check the "table_prefix" value to ensure that there will not be a clash with any existing tables in your database.

6.) Copy the contents of **templates** folder to **[directory-for-app]/templates/** folder

7.) Now in your browser goto URL:  

**http://localhost:8000/admin/provider/dashboard** if you are using php built in server  
OR  
**http://www.yourdomain.com/admin/provider/dashboard** if you have configured a domain on your server  
OR
Click **Dashboard** menu option and you will see list of available modules, click **Provider manager**  

Now click link at bottom of page **Setup Database**: This will create all necessary database tables with table_prefix as defined above.  
Thats it, you are good to go. Select **Service provider** Tab and: Add all your service providers and upload any supporting documentation linked to them.  
Now you can assign tasks to any service provider and capture the task progress in linked diary. Reports layout task history in a simple and accessible format.
