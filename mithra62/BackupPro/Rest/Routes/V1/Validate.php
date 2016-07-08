<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/V1/Validate.php
 */
 
namespace mithra62\BackupPro\Rest\Routes\V1;
use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

/**
 * Backup Pro - Validate REST Route
 *
 * Contains the REST specific verbs for the API
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Validate extends RestController {
    
    /**
     * Maps the available HTTP verbs we support for groups of data
     *
     * @var array
     */
    protected $collection_options = array(
        'OPTIONS'
    );
    
    /**
     * Maps the available HTTP verbs for single items
     *
     * @var array
    */
    protected $resource_options = array(
        'POST',
        'OPTIONS'
    );
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::put()
     */
    public function post($id = false) {
        
        $data = $this->getBodyData();
        if(!$data || !is_array($data) || count($data) == '0')
        {
            return $this->view_helper->renderError(422, 'unprocessable_entity');
        }
        
        $variables['form_data'] = array_merge(array('db_backup_ignore_tables' => '', 'db_backup_ignore_table_data' => ''), $data);
        $backup = $this->services['backups'];
        $backups = $backup->setBackupPath($this->settings['working_directory'])->getAllBackups($this->settings['storage_details']);
        $data['meta'] = $backup->getBackupMeta($backups);
        $extra = array('db_creds' => $this->platform->getDbCredentials());
        $settings_errors = $this->services['settings']->validate($data, $extra);
        $hal = $this->view_helper->prepareValidateCollection('/validate', $settings_errors);
        
        return $this->view_helper->renderOutput($hal);
    }
}
