<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Controllers/Console.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers;

use mithra62\BackupPro\Platforms\Console AS Platform;
use mithra62\BackupPro\Traits\Controller;
use mithra62\BackupPro\Platforms\View\Wordpress AS WordpressView;
use mithra62\BackupPro\BackupPro;

/**
 * Backup Pro - Console Base Controller
 *
 * Starts the Controllers up
 *
 * @package 	BackupPro\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Console implements BackupPro
{
    use Controller;
    
    /**
     * The abstracted platform object
     * @var \mithra62\Platforms\Eecms
     */
    protected $platform = null;
    
    /**
     * The Backup Pro Settings
     * @var array
     */
    protected $settings = array();
    
    /**
     * A container of system messages and errors
     * @var array
     */
    protected $errors = array();
    
    /**
     * The View Helper 
     * @var \mithra62\BackupPro\Platforms\View\Eecms
     */
    protected $view_helper = null;
    
    /**
     * The backup helper object
     * @var BackupPro
     */
    protected $backup_lib = null;
	
	/**
	 * An instance of the BackupPro object
	 * @var BackupPro
	 */
	protected $context = null;
    
    /**
     * Set it up
     * @param unknown $id
     * @param string $module
     */
    public function __construct( $config = array() )
    {
        $this->initController();
        $this->platform = new Platform();
        $this->platform->setConfig($config);
        $this->m62->setService('platform', function($c) {
            return $this->platform;
        });
        
        $this->m62->setDbConfig($this->platform->getDbCredentials());
        $this->settings = $this->services['settings']->get();
        $this->errors = $this->services['errors']->checkWorkingDirectory($this->settings['working_directory'])
                                                 ->checkStorageLocations($this->settings['storage_details'])
                                                 ->licenseCheck($this->settings['license_number'], $this->services['license'])
                                                 ->getErrors();
        
        $this->view_helper = new WordpressView($this->services['lang'], $this->services['files'], $this->services['settings'], $this->services['encrypt'], $this->platform);
        $this->m62->setService('view_helpers', function($c) {
            return $this->view_helper;
        });
        
        $this->console = $this->services['console'];
        $this->displayErrors();
    }
    
    /**
     * Boostrap for the Console interface
     */
    public function run()
    {
        $strict = in_array('--strict', $_SERVER['argv']);
        $args = $this->console->getArgs(array($strict));
        
        //help 
        if ($args['help']) 
        {
            $this->displayHelp($args);
        }
        
        //version
        if ($args['version']) 
        {
            $this->console->outputPageBreak();
            echo $this->services['lang']->__(self::name).' ('.self::version.')';
            $this->console->outputLine();
            $this->console->outputPageBreak();
        }
        
        //back things up
        if (!empty($args['backup']))
        {
            switch($args['backup'])
            {
                case 'files':
                case 'file':
                    $this->actionFile();
                break;
                case 'integrity':
                    $this->actionIntegrity();
                break;
                case 'database':
                    $this->actionDatabase();
                break;
            }
        }
    }
    
    /**
     * Wrapper to output any system errors BEFORE anything else
     * @return void
     */
    protected function displayErrors()
    {
        if( $this->errors )
        {

            $this->console->outputLine();
            $this->console->outputLine('uh_oh_there_are_issues');
            $this->console->outputPageBreak();
            
            foreach($this->errors AS $type => $value)
            {
                $this->console->outputError($value);
            }
            $this->console->outputPageBreak();
            $this->console->outputLine('fix_console_errors_instructions');
            exit;
        }
    }
    
    /**
     * Displays the Console Help screen
     * @param \cli\Arguments $args
     * @return void
     */
    protected function displayHelp(\cli\Arguments $args)
    {
        $this->console->outputPageBreak();
        $this->console->outputLine($this->services['lang']->__('backup_pro_module_name').' (v'.self::version.')', false);
        $this->console->outputPageBreak();
        echo $args->getHelpScreen();
            $this->console->outputLine();
        $this->console->outputPageBreak();
    }
    
    /**
     * Backups up the database
     * @param string $notify Set to "yes" to send an email on backup completion
     */
    public function actionDatabase( $notify="yes" )
    {
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $errors = $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])
                                       ->checkBackupDirs($backup->getStorage())
                                       ->getErrors();
    
        $this->displayErrors();
        $db_info = $this->platform->getDbCredentials();
        $backup_paths = array();
        $backup_paths['database'] = $backup->setDbInfo($db_info)->database($db_info['database'], $this->settings, $this->services['shell']);
    
        $this->cleanup($backup)->notify($notify, $backup_paths, $backup);
    }
    
    /**
     * Backups up the files
     * @param string $notify Set to "yes" to send an email on backup completion
     */
    public function actionFile( $notify="yes" )
    {
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $this->errors = $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])
                                             ->checkBackupDirs($backup->getStorage())
                                             ->checkFileBackupLocations($this->settings['backup_file_location'])
                                             ->getErrors();
        
        $this->displayErrors();
        $backup_paths = array();
        $backup_paths['files'] = $backup->files($this->settings, $this->services['files'], $this->services['regex']);
        $this->cleanup($backup)->notify($notify, $backup_paths, $backup);
    }
    
    /**
     * The integrity agent route
     */
    public function actionIntegrity()
    {
        @ini_set('memory_limit', -1);
        @set_time_limit(0); //limit the time to 1 hours
    
        //grab the backup and storage objects and set them up
        $backup = $this->services['backups']->setLocations($this->settings['storage_details'])
                       ->setBackupPath($this->settings['working_directory']);
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $backup->getIntegrity()->setFile($this->services['files'])->setStorage($storage->getStorage());
        //first, check the backup state
        //ee()->integrity_agent->monitor_backup_state();
    
        //now check the backups and ensure they're all valid
        $backups = $backup->getAllBackups($this->settings['storage_details']);
        $type = ($this->settings['last_verification_type'] == 'database' ? 'files' : 'database') ;
    
        //ok, this is a little bash over the head to FUCING ENSURE we're NOT using the production db for database testing!
        //THAT WOULD BE BAD. So... bad... uh... coooooodddddddde...
        if( $type == 'database' && empty($this->settings['db_verification_db_name']) )
        {
            $type = 'files';
        }
    
        $total = 0;
        foreach($backups[$type] AS $details)
        {
            if( empty($details['verified']) || $details['verified'] == '0')
            {
                if($type == 'files')
                {
                    $file = $storage->getStorage()->getFileBackupNamePath($details['details_file_name']);
                }
                else
                {
                    $file = $storage->getStorage()->getDbBackupNamePath($details['details_file_name']);
                    $backup->getIntegrity()->setDbConf($this->platform->getDbCredentials())
                                            ->setTestDbName($this->settings['db_verification_db_name'])
                                            ->setShell($this->services['shell'])
                                            ->setSettings($this->settings)
                                            ->setBackupInfo($details)
                                            ->setRestore($this->services['restore']);
    
                }
                 
                $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
                if( $backup->getIntegrity()->checkBackup($backup_info, $type) )
                {
                    $status = 'success';
                    $total++;
                }
                else
                {
                    $status = 'fail';
                }
    
                $path = rtrim($this->settings['working_directory'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$type;
                $this->services['backup']->getDetails()->addDetails($details['file_name'], $path, array('verified' => $status));
            }
             
            if( $total >= $this->settings['total_verifications_per_execution'])
            {
                break;
            }
        }
    
        $data = array(
            'last_verification_type' => $type,
            'last_verification_time' => time()
        );
        $this->services['settings']->update($data);
    
        exit;
    }
    
    /**
     * Runs the Cleanup routines
     * @param \mithra62\BackupPro\Backup $backup
     * @return \Craft\BackupCommand
     */
    private function cleanup($backup)
    {
        $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                        ->getAllBackups($this->settings['storage_details']);
        $backup->getStorage()->getCleanup()->setStorageDetails($this->settings['storage_details'])
                             ->setBackups($backups)
                             ->setDetails($this->services['backups']->getDetails())
                             ->autoThreshold($this->settings['auto_threshold'])
                             ->counts($this->settings['max_file_backups'], 'files')
                             ->duplicates($this->settings['allow_duplicates']);
    
        return $this;
    }
    
    /**
     * Runs the notify routines
     * @param string $notify
     * @param array $backup_paths
     * @param \mithra62\BackupPro\Backup $backup
     * @return \Craft\BackupCommand
     */
    private function notify($notify, array $backup_paths, $backup)
    {
        if( count($this->settings['cron_notify_emails']) >= 1 )
        {
            $notify = $this->services['notify'];
            $notify->getMail()->setConfig($this->platform->getEmailConfig());
            foreach($backup_paths As $type => $path)
            {
                $cron = array($type => $path);
                $notify->setBackup($backup)->sendBackupCronNotification($this->settings['cron_notify_emails'], $cron, $type);
            }
        }
        return $this;
    }    
}