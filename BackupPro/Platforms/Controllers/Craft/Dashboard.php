<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Craft/Dashboard.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers\Craft;

/**
 * Backup Pro - Craft Dashboard Controller
 *
 * Contains the Dashboard Controller Actions for Craft
 *
 * @package 	BackupPro\Craft\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait Dashboard
{
    /**
     * The Dashboard view
     */
    public function index()
    {
        if( empty($this->settings['working_directory']) )
        {
            \Craft\craft()->userSession->setFlash('error', $this->services['lang']->__('working_dir_not_setup'));
            $this->redirect('backuppro/settings');
            exit;
        }
        
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        
        $backup_meta = $backup->getBackupMeta($backups);
        $available_space = $backup->getAvailableSpace($this->settings['auto_threshold'], $backups);
        
        $backups = $backups['database'] + $backups['files'];
        krsort($backups, SORT_NUMERIC);
        
        if(count($backups) > $this->settings['dashboard_recent_total'])
        {
            //we have to remove a few
            $filtered_backups = array();
            $count = 1;
            foreach($backups AS $time => $backup)
            {
                $filtered_backups[$time] = $backup;
                if($count >= $this->settings['dashboard_recent_total'])
                {
                    break;
                }
                $count++;
            }
            $backups = $filtered_backups;
        }
        
        $variables = array(
            'settings' => $this->settings,
            'backup_meta' => $backup_meta,
            'backups' => $backups,
            'available_space' => $available_space,
            'errors' => $this->errors
        );
        
        $template = 'backuppro/dashboard';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * The view database backups view
     */
    public function db_backups()
    {
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $backup_meta = $backup->getBackupMeta($backups);
        
        $variables = array(
            'settings' => $this->settings,
            'backup_meta' => $backup_meta,
            'backups' => $backups,
            'errors' => $this->errors
        );
        
        $template = 'backuppro/database_backups';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * The view file backups view
     */
    public function file_backups()
    {
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $backup_meta = $backup->getBackupMeta($backups);
        
        $variables = array(
            'settings' => $this->settings,
            'backup_meta' => $backup_meta,
            'backups' => $backups,
            'errors' => $this->errors
        );
        
        $template = 'backuppro/file_backups';
        $this->renderTemplate($template, $variables);
    }
}