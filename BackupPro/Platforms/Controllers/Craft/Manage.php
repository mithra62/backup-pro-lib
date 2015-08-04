<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Controllers/Craft/Manage.php
 */
 
namespace mithra62\BackupPro\Platforms\Controllers\Craft;

/**
 * Backup Pro - Craft Manage Backup Controller
 *
 * Contains the Manage Backup Controller Actions for Craft
 *
 * @package 	BackupPro\Craft\Controllers
 * @author		Eric Lamb <eric@mithra62.com>
 */
trait Manage
{
    /**
     * Download a backup action
     */
    public function download()
    {
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode(\Craft\craft()->request->getParam('id'));
        $type = \Craft\craft()->request->getParam('type');
        $storage = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        if($type == 'files')
        {
            $file = $storage->getStorage()->getFileBackupNamePath($file_name);
        }
        else
        {
            $file = $storage->getStorage()->getDbBackupNamePath($file_name);
        }
        
        $backup_info = $this->services['backups']->setLocations($this->settings['storage_details'])->getBackupData($file);
        $download_file_path = false;
        foreach($backup_info['storage_locations'] AS $storage_location)
        {
            if( $storage_location['obj']->canDownload() )
            {
                $download_file_path = $storage_location['obj']->getFilePath($backup_info['file_name'], $backup_info['backup_type']); //next, get file path
                break;
            }
        }
        
        if($download_file_path && file_exists($download_file_path))
        {
            //$new_name = $backup->getStorage()->makePrettyFilename($file_name, $type, craft()->config->get('siteName'));
            $this->services['files']->fileDownload($download_file_path);
            exit;
        }
        else
        {
            \Craft\craft()->userSession->setFlash('error', $this->services['lang']->__('db_backup_not_found'));
            $this->redirect('backuppro');
            exit;
        }
    }
    
    /**
     * AJAX Action for updating a backup note
     */
    public function update_backup_note()
    {
        $this->requireAjaxRequest();
        $encrypt = $this->services['encrypt'];
        $file_name = \Craft\craft()->request->getParam('backup');
        $backup_type = \Craft\craft()->request->getParam('backup_type');
        $note_text = \Craft\craft()->request->getParam('note_text');
        if($note_text && $file_name)
        {
            $path = rtrim($this->settings['working_directory'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$backup_type;
            $this->services['backup']->getDetails()->addDetails($file_name, $path, array('note' => $note_text));
            echo json_encode(array('success'));
        }
        exit;
    }
    
    /**
     * Delete Backup Confirmation Action
     */
    public function delete_backup_confirm()
    {
        $delete_backups = \Craft\craft()->request->getParam('backups');
        $type = \Craft\craft()->request->getParam('type');
        $backups = $this->validateBackups($delete_backups, $type);
        $variables = array(
            'settings' => $this->settings,
            'backups' => $backups,
            'backup_type' => $type,
            'errors' => $this->errors
        );
        
        $template = 'backuppro/delete_confirm';
        $this->renderTemplate($template, $variables);
    }
    
    /**
     * Delete Backup Action
     */
    public function delete_backups()
    {
        $this->requirePostRequest();
        $delete_backups = \Craft\craft()->request->getParam('backups');
        $type = \Craft\craft()->request->getParam('type');
        $backups = $this->validateBackups($delete_backups, $type);
        if( $this->services['backups']->setBackupPath($this->settings['working_directory'])->removeBackups($backups) )
        {
            \Craft\craft()->userSession->setFlash('notice', $this->services['lang']->__('backups_deleted'));
            $this->redirect('backuppro');
        }
        else
        {
            \Craft\craft()->userSession->setFlash('error', $this->services['lang']->__('backup_delete_failure'));
            $this->redirect('backuppro');
        }
    }
}