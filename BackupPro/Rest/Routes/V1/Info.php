<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/V1/Info.php
 */
 
namespace mithra62\BackupPro\Rest\Routes\V1;
use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

/**
 * Backup Pro - Site Info REST Route
 *
 * Contains the REST specific verbs for the API
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Info extends RestController {
    
    public function get($id = false)
    {
        switch($id)
        {
            case 'options':
                return $this->settingsOptions();
            break;
            
            case 'site':
                return $this->siteDetails();
            break;
            
            case 'ini':
                return $this->iniAll();
            break;
        }
        
        return $this->view_helper->renderError(404, 'not_found');
    }
    
    private function settingsOptions()
    {
        $options = array(
            'db_tables' => $this->services['db']->getTables(),
            'backup_cron_commands' => $this->platform->getBackupCronCommands($this->settings),
        );
        
        $hal = $this->view_helper->prepareSystemInfoCollection('/info/options', $options);
        return $this->view_helper->renderOutput($hal);
    }
    
    private function siteDetails()
    {
        
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $available_space = $backup->getAvailableSpace($this->settings['auto_threshold'], $backups);
        $backup_meta = $backup->getBackupMeta($backups);
        $parts = explode('\\', get_class($this->platform));
        $data = array(
            'site_url' => $this->platform->getSiteUrl(),
            'site_name' => $this->platform->getSiteName(),
            'platform' => end($parts),
            'file_backup_total' => count($backups['files']),
            'database_backup_total' => count($backups['database']),
            'backup_prevention_errors' => $this->backupPreventionErrors(),
            'first_backup' => ($backup_meta['global']['oldest_backup_taken_raw'] != '' ? date('Y-m-d H:i:s', $backup_meta['global']['oldest_backup_taken_raw']) : ''),
            'last_backup' => ($backup_meta['global']['newest_backup_taken_raw'] != '' ? date('Y-m-d H:i:s', $backup_meta['global']['newest_backup_taken_raw']) : '')
        );
        $hal = $this->view_helper->prepareSystemInfoCollection('/info/site', $data);
        
        return $this->view_helper->renderOutput($hal);
    }
    
    private function iniAll()
    {
        $data = ini_get_all();
        $hal = $this->view_helper->prepareSystemInfoCollection('/info', $data);
        return $this->view_helper->renderOutput($hal);
    }
    
    private function backupPreventionErrors()
    {
        $errors = $this->services['errors']->clearErrors()->checkWorkingDirectory($this->settings['working_directory'])
                                            ->checkStorageLocations($this->settings['storage_details'])
                                            ->checkFileBackupLocations($this->settings['backup_file_location'])
                                            ->getErrors();
        
        foreach($errors As $key => $value) {
            $errors[$key] = $this->services['lang']->__($value);
        }
        
        return $errors;
    }
}