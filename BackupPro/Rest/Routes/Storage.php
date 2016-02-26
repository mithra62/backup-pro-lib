<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Rest/Routes/Storage.php
 */
 
namespace mithra62\BackupPro\Rest\Routes;
use mithra62\BackupPro\Platforms\Controllers\Rest AS RestController;

/**
 * Backup Pro - Storage REST Route
 *
 * Contains the REST specific verbs for the API
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Storage extends RestController {

    /**
     * Maps the available HTTP verbs we support for groups of data
     *
     * @var array
     */
    protected $collection_options = array(
        'GET',
        'OPTIONS'
    );

    /**
     * Maps the available HTTP verbs for single items
     *
     * @var array
    */
    protected $resource_options = array(
        'GET',
        'PUT',
        'OPTIONS'
    );
    
    /**
     * The default Storage form field values
     * @var unknown
     */
    public $storage_form_data_defaults = array(
        'storage_location_name' => '',
        'storage_location_file_use' => '1',
        'storage_location_status' => '1',
        'storage_location_db_use' => '1',
        'storage_location_include_prune' => '1',
    );
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::get()
     */
    public function get($id = false)
    {
        if($id){ 
            
            if(!$this->settings['storage_details'][$id])
            {
                return $this->view_helper->renderError(404, 'not_found');
            }
            
            $data = $this->settings['storage_details'][$id];
            $hal = $this->view_helper->prepareStorageLocationCollection('/storage/'.$id, $data);
            
        } else {
            $meta = array('total_locations' => count($this->settings['storage_details']));
            $data = $this->settings['storage_details'];
            $hal = $this->view_helper->prepareStorageLocationCollection('/storage', $meta, $data);
        }
        
        return $this->view_helper->renderOutput($hal);
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::post()
     */
    public function post()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if(!$data || !is_array($data) || count($data) == '0')
        {
            return $this->view_helper->renderError(422, 'unprocessable_entity');
        }
        
        if(!isset($data['engine'])){
            $error = array('\'engine\' must be included in the POST data...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }
        
        $available_storage_engines = $this->services['backup']->getStorage()->getAvailableStorageDrivers();
        if( !isset($available_storage_engines[$data['engine']]) )
        {
            $error = array('\''.$data['engine'].'\' isn\'t a valid storage engine...');
            return $this->view_helper->renderError(422, 'unprocessable_entity', $error);
        }   

        $data = array_merge($this->storage_form_data_defaults, $data);
        $settings_errors = $this->services['backup']->getStorage()->validateDriver($this->services['validate'], $data['engine'], $data, $this->settings['storage_details']);
        if( $settings_errors )
        {
            return $this->view_helper->renderError(422, 'unprocessable_entity', $settings_errors);
        }
        
        $location_id = $this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->create($data['engine'], $data);
        if( $location_id )
        {
            $settings = $this->services['settings']->get(true);
            $storage_data = $settings['storage_details'][$location_id];
            $hal = $this->view_helper->prepareStorageLocationCollection('/storage/'.$location_id, $storage_data);
            return $this->view_helper->renderOutput($hal);            
        }   
        
        return $this->view_helper->renderError(500, 'unprocessable_entity');
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::put()
     */
    public function put($id = false) 
    {
        if(!$id || !$this->settings['storage_details'][$id])
        {
            return $this->view_helper->renderError(404, 'not_found');
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        if(!$data || !is_array($data) || count($data) == '0')
        {
            return $this->view_helper->renderError(422, 'unprocessable_entity');
        }
        
        //prepare the data
        $storage_data = $this->settings['storage_details'][$id];
        $data = array_merge($this->storage_form_data_defaults, $storage_data, $data);
        
        //check for errors
        $data['location_id'] = $id;
        $settings_errors = $this->services['backup']->getStorage()
                                ->validateDriver($this->services['validate'], 
                                    $storage_data['storage_location_driver'], 
                                    $data, $this->settings['storage_details']
                                );
        
        //handle errors
        if( $settings_errors )
        {
            return $this->view_helper->renderError(422, 'unprocessable_entity', $settings_errors);
        }
        
        //update the storage location
        if( $this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->update($id, $data) )
        {
            $hal = $this->view_helper->prepareStorageLocationCollection('/storage/'.$id, $data);
            return $this->view_helper->renderOutput($hal);
        }
        
        return $this->view_helper->renderError(500, 'unprocessable_entity');
        
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\Controllers\Rest::delete()
     */
    public function delete($id = false)
    {
        if(!$id || !$this->settings['storage_details'][$id])
        {
            return $this->view_helper->renderError(404, 'not_found');
        }
        
        if( count($this->settings['storage_details']) <= 1 )
        {
            $settings_errors = array($this->services['lang']->__('min_storage_location_needs'));
            return $this->view_helper->renderError(422, 'unprocessable_entity', $settings_errors);
        }
        
        $backups = $this->services['backups']->setBackupPath($this->settings['working_directory'])
                                             ->getAllBackups($this->settings['storage_details'], $this->services['backup']->getStorage()->getAvailableStorageDrivers());   
        
        if( !$this->services['backup']->getStorage()->getLocations()->setSetting($this->services['settings'])->remove($id, array(), $backups) )
        {
            return $this->view_helper->renderError(500, 'unprocessable_entity');
        }
    }
}