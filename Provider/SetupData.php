<?php
namespace App\Provider;

use Seriti\Tools\SetupModuleData;

class SetupData extends SetupModuledata
{

    public function setupSql()
    {
        $this->tables = ['provider','task','file','task_diary'];

        $this->addCreateSql('provider',
                            'CREATE TABLE `TABLE_NAME` (
                              `provider_id` int(11) NOT NULL AUTO_INCREMENT,
                              `name` varchar(64) NOT NULL,
                              `email` varchar(64) NOT NULL,
                              `status` varchar(16) NOT NULL,
                              `contact_name` varchar(64) NOT NULL,
                              `email_alt` varchar(64) NOT NULL,
                              PRIMARY KEY (`provider_id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8'); 

        $this->addCreateSql('task',
                            'CREATE TABLE `TABLE_NAME` (
                              `task_id` int(11) NOT NULL AUTO_INCREMENT,
                              `name` varchar(64) NOT NULL,
                              `description` text NOT NULL,
                              `status` varchar(64) NOT NULL,
                              `date_create` date NOT NULL,
                              `provider_id` int(11) NOT NULL,
                              `date_due` date NOT NULL,
                              `trigger_clause` varchar(64) NOT NULL,
                              `trigger_text` text NOT NULL,
                              `action_frequency` varchar(64) NOT NULL,
                              `action_committee` varchar(64) NOT NULL,
                              `user_id` int(11) NOT NULL,
                              `remarks` text NOT NULL,
                              PRIMARY KEY (`task_id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8'); 

        $this->addCreateSql('task_diary',
                            'CREATE TABLE `TABLE_NAME` (
                              `diary_id` int(11) NOT NULL AUTO_INCREMENT,
                              `task_id` int(11) NOT NULL,
                              `date` datetime NOT NULL,
                              `notes` text NOT NULL,
                              `subject` varchar(255) NOT NULL,
                              PRIMARY KEY (`diary_id`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8'); 

        $this->addCreateSql('file',
                            'CREATE TABLE `TABLE_NAME` (
                              `file_id` int(10) unsigned NOT NULL,
                              `title` varchar(255) NOT NULL,
                              `file_name` varchar(255) NOT NULL,
                              `file_name_orig` varchar(255) NOT NULL,
                              `file_text` longtext NOT NULL,
                              `file_date` date NOT NULL,
                              `location_id` varchar(64) NOT NULL,
                              `location_rank` int(11) NOT NULL,
                              `key_words` text NOT NULL,
                              `description` text NOT NULL,
                              `file_size` int(11) NOT NULL,
                              `encrypted` tinyint(1) NOT NULL,
                              `file_name_tn` varchar(255) NOT NULL,
                              `file_ext` varchar(16) NOT NULL,
                              `file_type` varchar(16) NOT NULL,
                              PRIMARY KEY (`file_id`),
                              KEY `service_file_idx1` (`location_id`),
                              FULLTEXT KEY `service_file_idx2` (`key_words`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8');

        //initialisation
        $this->addInitialSql('INSERT INTO `TABLE_PREFIXprovider` (name,email,status,contact_name) '.
                             'VALUES("My first provider","bob@provider.com","OK","bob")');
        

        //updates use time stamp in ['YYYY-MM-DD HH:MM'] format, must be unique and sequential
        //$this->addUpdateSql('YYYY-MM-DD HH:MM','Update TABLE_PREFIX--- SET --- "X"');
    }
}