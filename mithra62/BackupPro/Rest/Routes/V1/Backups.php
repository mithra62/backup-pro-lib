<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/V1/Backups.php
 */
 
namespace mithra62\BackupPro\Rest\Routes\V1;

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
            }
            else
            {
                $backups = $backups['files'];
            }
        }
        else
        {
            $backups = $backups['database'] + $backups['files'];
        }
        
        krsort($backups, SORT_NUMERIC);
        $backup_meta = array_merge($backup_meta, array('available_space' => $available_space));
        
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
        $data = $this->getBodyData();
        $id = (isset($data['id']) ? $data['id'] : $this->platform->getPost('id'));
        $backup_type = (isset($data['type']) ? $data['type'] : $this->platform->getPost('type'));
        
        //ensure params
        if(!$id)
        {
            $error = array('\'id\' must be defined in the query string or an array in body json...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }
        
        if(!$backup_type){
            $error = array('\'type\' must be defined in the query string or in the body json...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }   
        
        try {
            $delete_backups = (!is_array($id) ? array($id) : $id);
            $backups = $this->validateBackups($delete_backups, $backup_type);
        }
        catch (\mithra62\BackupPro\Exceptions\Backup\DetailsException $e) { 
            return $this->view_helper->renderError(404, 'not_found');
        }
        
        $this->services['backups']->setBackupPath($this->settings['working_directory'])->removeBackups($backups);
        return $this->get();
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::put()
     */
    public function put($id = false) { 
        $data = $this->getBodyData();
        $id = (isset($data['id']) ? $data['id'] : $this->platform->getPost('id'));
        $backup_type = (isset($data['type']) ? $data['type'] : $this->platform->getPost('type'));
        
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
        $file_name = $id;
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
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::post()
     */
    public function post()
    {
        $data = $this->getBodyData();
        $backup_type = (isset($data['type']) ? $data['type'] : 'database');
        
        switch($backup_type)
        {
            case 'database':
                $this->backup_database();
            break;
            
            case 'files':
            case 'file':
                $this->backup_files();
            break;
            
        }
    }
    
    protected function backup_database()
    {
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])
              ->checkWorkingDirectory($this->settings['working_directory'])
              ->checkBackupDirs($backup->getStorage());
        if( $error->totalErrors() == '0' )
        {
            ini_set('memory_limit', -1);
            set_time_limit(0);
    
            $db_info = $this->platform->getDbCredentials();
            if( $backup->setDbInfo($db_info)->database($db_info['database'], $this->settings, $this->services['shell']) )
            {
                $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                                                     ->getAllBackups($this->settings['storage_details']);
    
                $backup->getStorage()->getCleanup()->setStorageDetails($this->settings['storage_details'])
                                     ->setBackups($backups)
                                     ->setDetails($this->services['backups']->getDetails())
                                     ->autoThreshold($this->settings['auto_threshold'])
                                     ->counts($this->settings['max_db_backups'])
                                     ->duplicates($this->settings['allow_duplicates']);
                
                
                //output backup details
                exit;
            }
        }
        else
        {
            //output errors
            
            exit;
            ee()->session->set_flashdata('message_error', $this->services['lang']->__($error->getError()));
            $this->platform->redirect($this->url_base.'db_backups');
        }
    
        exit;
    }
    
    public function backup_files()
    {
        $error = $this->services['errors'];
        $backup = $this->services['backup']->setStoragePath($this->settings['working_directory']);
        $error->clearErrors()->checkStorageLocations($this->settings['storage_details'])
                             ->checkBackupDirs($backup->getStorage())
                             ->checkWorkingDirectory($this->settings['working_directory'])
                             ->checkFileBackupLocations($this->settings['backup_file_location']);
        if( $error->totalErrors() == 0 )
        {
            ini_set('memory_limit', -1);
            set_time_limit(0);
            if( $backup->files($this->settings, $this->services['files'], $this->services['regex']) )
            {
                $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                ->getAllBackups($this->settings['storage_details']);
    
                $backup->getStorage()->getCleanup()->setStorageDetails($this->settings['storage_details'])
                                     ->setBackups($backups)
                                     ->setDetails($this->services['backups']->getDetails())
                                     ->autoThreshold($this->settings['auto_threshold'])
                                     ->counts($this->settings['max_file_backups'], 'files')
                                     ->duplicates($this->settings['allow_duplicates']);
                
                //output backup details
                
                exit;
            }
        }
        else
        {
            //output errors
            
            exit;
            
            $url = $this->url_base.'file_backups';
            if( $error->getError() == 'no_backup_file_location' )
            {
                $url = $this->url_base.'settings&section=files';
            }
    
            ee()->session->set_flashdata('message_error', $this->services['lang']->__($error->getError()));
            $this->platform->redirect($url);
        }
    }
}
