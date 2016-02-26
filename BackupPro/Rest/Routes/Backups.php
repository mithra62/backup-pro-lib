<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/Backups.php
 */
 
namespace mithra62\BackupPro\Rest\Routes;

use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

/**
 * Backup Pro - Backups REST Route
 *
 * Contains the REST specific verbs for teh API
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Backups extends RestController {

    /**
     * Maps the available HTTP verbs we support for groups of data
     *
     * @var array
     */
    protected $collection_options = array(
        'GET',
        'POST',
        'OPTIONS'
    );
    
    /**
     * Maps the available HTTP verbs for single items
     *
     * @var array
    */
    protected $resource_options = array(
        'GET',
        'POST',
        'DELETE',
        'PUT',
        'OPTIONS'
    );
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::get()
     */
    public function get($id = false) 
    { 
        $id = ($id ? $id : $this->platform->getPost('id'));
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $available_space = $backup->getAvailableSpace($this->settings['auto_threshold'], $backups);
        $backup_meta = $backup->getBackupMeta($backups);
        
        if(!$id && $this->platform->getPost('type'))
        {
            if($this->platform->getPost('type') == 'db')
            {
                $backups = $backups['database'];
                $backup_meta = $backup_meta['database'];
            }
            else
            {
                $backups = $backups['files'];
                $backup_meta = $backup_meta['files'];
            }
        }
        else
        {
            $backups = $backups['database'] + $backups['files'];
            $backup_meta = $backup_meta['global'];
        }
        
        krsort($backups, SORT_NUMERIC);
        $backup_meta = $backup_meta + $available_space;
        
        if($id)
        {
            $hal = $this->view_helper->prepareBackupCollection('/backups', $backups);
        }
        else
        {
            $hal = $this->view_helper->prepareBackupCollection('/backups', $backup_meta, $backups);
        }

        return $this->view_helper->renderOutput($hal);
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::delete()
     */
    public function delete($id = false) 
    { 
        $id = $this->platform->getPost('id');
        $backup_type = $this->platform->getPost('type');
        
        //ensure params
        if(!$id)
        {
            $error = array('\'id\' must be defined in the query string...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }
        
        if(!$backup_type){
            $error = array('\'type\' must be defined in the query string...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }   
        
        try {
            $delete_backups = array($id);
            $backups = $this->validateBackups($delete_backups, $backup_type);
        }
        catch (\mithra62\BackupPro\Exceptions\Backup\DetailsException $e) { 
            return $this->view_helper->renderError(404, 'not_found');
        }
        
        $this->services['backups']->setBackupPath($this->settings['working_directory'])->removeBackups($backups);
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::put()
     */
    public function put($id = false) { 
        $id = $this->platform->getPost('id');
        $backup_type = $this->platform->getPost('type');
        $data = json_decode(file_get_contents("php://input"), true);
        
        //ensure params
        if(!$id)
        {
            $error = array('\'id\' must be defined in the query string...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }
        
        if(!$backup_type){
            $error = array('\'type\' must be defined in the query string...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }
        
        //ensure backup exists
        $encrypt = $this->services['encrypt'];
        $file_name = $encrypt->decode($id);
        if($file_name && isset($data['backup_note']))
        {
            $path = rtrim($this->settings['working_directory'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$backup_type;
            //$file_data = $this->services['backup']->getDetails()->getDetails($file_name, $path);
            //print_r($file_data);
            $this->services['backup']->getDetails()->addDetails($file_name, $path, array('note' => $data['backup_note']));
        }
        
        $file_data = $this->services['backup']->getDetails()->getDetails($file_name, $path);
        $hal = $this->view_helper->prepareBackupCollection('/backups', array(), array($file_data));
        return $this->view_helper->renderOutput($hal);
    }
}
