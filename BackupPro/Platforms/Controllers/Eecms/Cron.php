<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Eecms/Cron.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers\Eecms;

use mithra62\Traits\Log;
use mithra62\BackupPro\Exceptions\BackupException;

/**
 * Backup Pro - Eecms Cron Controller
 *
 * Contains the Cron Controller Actions for ExpressionEngine
 *
 * @package 	BackupPro\Eecms\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait Cron
{
    use Log;
    
    /**
     * The methods anyone can access
     * @var array
     */
    protected $allowAnonymous = array(
        'actionBackup',
        'actionIntegrityAgent'
    );
    
    /**
     * The Backup Cron
    */
    public function cron()
    {
        @session_write_close();
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $errors = $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])->checkBackupDirs($backup->getStorage())->getErrors();
    
        if( $error->totalErrors() == '0' )
        {
            ini_set('memory_limit', -1);
            set_time_limit(0);
    
            $backup_type = ee()->input->get_post('type');
            $backup_paths = array();
            switch($backup_type)
            {
                case 'db':
                    $db_info = $this->platform->getDbCredentials();
                    $backup_paths['database'] = $backup->setDbInfo($db_info)->database($db_info['database'], $this->settings, $this->services['shell']);
                    break;
    
                case 'file':
                    $backup_paths['files'] = $backup->files($this->settings, $this->services['files'], $this->services['regex']);
                    break;
            }
            
            $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                                                 ->getAllBackups($this->settings['storage_details']);
            $backup->getStorage()->getCleanup()->setStorageDetails($this->settings['storage_details'])
                                               ->setBackups($backups)
                                               ->setDetails($this->services['backups']->getDetails())
                                               ->autoThreshold($this->settings['auto_threshold'])
                                               ->counts($this->settings['max_file_backups'], 'files')
                                               ->duplicates($this->settings['allow_duplicates']);            
    
            //now send the notifications (if any)
            if(count($backup_paths) >= 1 && count($this->settings['cron_notify_emails']) >= 1)
            {
                $notify = $this->services['notify'];
                $notify->getMail()->setConfig($this->platform->getEmailConfig());
                foreach($backup_paths As $type => $path)
                {
                    $cron = array($type => $path);
                    $notify->setBackup($backup)->sendBackupCronNotification($this->settings['cron_notify_emails'], $cron, $type);
                }
            }
        }
        else
        {
            $this->logDebug($error->getError());
            throw new BackupException($error->getError());
        }
    
        exit;
    }
    
    /**
     * The Integrity Agent Cron
     */
    public function actionIntegrityAgent()
    {
        ini_set('memory_limit', -1);
        set_time_limit(0); //limit the time to 1 hours
    
        echo __METHOD__;
        exit;
    }
}