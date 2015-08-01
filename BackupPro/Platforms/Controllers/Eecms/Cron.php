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
            if(count($backup_paths) >= 1 && (is_array($this->settings['cron_notify_emails']) && count($this->settings['cron_notify_emails']) >= 1))
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
    public function integrity()
    {
        ini_set('memory_limit', -1);
        set_time_limit(0); //limit the time to 1 hours

        $backup = $this->services['backups']->setBackupPath($this->settings['working_directory']);
        
        
		ee()->backup_pro->set_db_info($this->db_conf);
		ee()->load->library('Backup_pro_integrity_agent', null, 'integrity_agent');
		if( !empty($this->settings['db_verification_db_name']) )
		{
			$this->db_conf['db_name'] = $this->settings['db_verification_db_name'];
			ee()->integrity_agent->set_db_conf($this->db_conf);
		}
		
		//first, check the backup state
		ee()->integrity_agent->monitor_backup_state();
		
		//now check the backups and ensure they're all valid
		$backups = ee()->backup_pro->get_backups();
		$type = ($this->settings['last_verification_type'] == 'database' ? 'files' : 'database') ;
		
		//ok, this is a little bash over the head to FUCING ENSURE we're NOT using the production db for database testing!
		//THAT WOULD BE BAD. So... bad... uh... coooooodddddddde... 
		if($type == 'database' && !ee()->integrity_agent->get_db_conf())
		{
			$type = 'files';
		}
		
		$total = 0;
		foreach($backups[$type] AS $backup)
		{
			if( empty($backup['details']['verified']) || $backup['details']['verified'] == '0')
			{
				if( ee()->integrity_agent->check_backup($backup, $type) )
				{
					$status = 'success';
					$total++;
				}
				else 
				{
					$status = 'fail';
				}
				
				$details_path = ee()->backup_pro->backup_dir.DIRECTORY_SEPARATOR.$type;
				ee()->backup_details->add_details($backup['file_name'], $details_path, array('verified' => $status));
			}
			
			if( $total >= $this->settings['total_verifications_per_execution'])
			{
				break;
			}
		}
		
		ee()->backup_pro_settings->update_setting('last_verification_type', $type);
		ee()->backup_pro_settings->update_setting('last_verification_time', time());
    }
}