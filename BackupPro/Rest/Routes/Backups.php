<?php
namespace mithra62\BackupPro\Rest\Routes;

use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

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
    public function delete($id) 
    { 
        echo __METHOD__;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::put()
     */
    public function put($id) { 
        echo __METHOD__;
    }
}
