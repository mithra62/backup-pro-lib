<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Craft/Restore.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers\Craft;

/**
 * Backup Pro - Craft Restore Backup Controller
 *
 * Contains the Restore Backup Controller Actions for Craft
 *
 * @package 	BackupPro\Craft\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait Restore
{
    /**
     * Restore database confirm
     */
    public function restore_confirm()
    {
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode(\Craft\craft()->request->getParam('id'));
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        
        $file = $storage->getStorage()->getDbBackupNamePath($file_name);
        $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
        $variables = array(
            'settings' => $this->settings,
            'backup' => $backup_info,
            'errors' => $this->errors
        );
        
        $template = 'backuppro/restore_confirm';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * Restore database action
     */
    public function restore_database()
    {
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode(\Craft\craft()->request->getParam('id'));
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        
        $file = $storage->getStorage()->getDbBackupNamePath($file_name);
        $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
        $restore_file_path = false;
        foreach($backup_info['storage_locations'] AS $storage_location)
        {
            if( $storage_location['obj']->canRestore() )
            {
                $restore_file_path = $storage_location['obj']->getFilePath($backup_info['file_name'], $backup_info['backup_type']); //next, get file path
                break;
            }
        }
        
        if($restore_file_path && file_exists($restore_file_path))
        {
            $db_info = $this->platform->getDbCredentials();
            if( $this->services['restore']->setDbInfo($db_info)->setBackupInfo($backup_info)->database($db_info['database'], $restore_file_path, $this->settings, $this->services['shell']) )
            {
                \Craft\craft()->userSession->setFlash('notice', $this->services['lang']->__('database_restored'));
                $this->redirect('backuppro/database_backups');
            }
        }
        else
        {
            \Craft\craft()->userSession->setFlash('error', $this->services['lang']->__('db_backup_not_found'));
            $this->redirect('backuppro');
            exit;
        }
    }
}