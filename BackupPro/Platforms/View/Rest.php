<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/View/Rest.php
 */
 
namespace mithra62\BackupPro\Platforms\View;

use mithra62\Platforms\View\Rest as RestView;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;
use mithra62\BackupPro\BackupPro;

/**
 * Backup Pro - REST View abstraction
 *
 * Contains the REST specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Rest extends RestView implements BackupPro
{
    use ViewHelpers;

    /**
     * The mapping of backup resource variables to their output name
     * @var array
     */
    protected $backup_output_map = array(
        'note' => 'note',
        'hash' => 'md5_hash',
        'locked' => 'locked',
        'item_count' => 'item_count',
        'uncompressed_size' => 'uncompressed_size',
        'compressed_size' => 'compressed_size',
        'created_date' => 'created_date',
        'backup_type' => 'backup_type',
        'verified' => 'verified',
        'time_taken' => 'time_taken',
        'max_memory' => 'max_memory',
        'file_name' => 'file_name',
        'file_size' => 'file_size',
    );
    

    /**
     * The mapping of backup resource variables to their output name
     * @var array
     */
    protected $backups_output_map = array(
        'newest_backup_taken_raw' => 'newest_backup_taken_date',
        'oldest_backup_taken_raw' => 'oldest_backup_taken_date',
        'total_space_used_raw' => 'total_space_used',
        'total_backups' => 'total_backups',
        'available_space' => 'available_space',
    );
    
    /**
     * The mapping of storage location resource variables to their output name
     * @var array
     */
    protected $backup_storage_location_output_map = array(
        'storage_location_name' => 'storage_location_name',
        'location_id' => 'storage_location_id',
        'storage_location_driver' => 'storage_location_driver',
        'storage_location_file_use' => 'storage_location_db_use',
        'storage_location_include_prune' => 'storage_location_include_prune',
        'storage_location_create_date' => 'storage_location_create_date',
    );
    
    /**
     * We return ALL settings, so instead of mapping to what we want
     * we map to what we DON'T want instead
     * @var array
     */
    protected $settings_remove_vars = array(
        'api_key',
        'api_secret',
        'storage_details',
        'mysqldump_command'
    );
    
    /**
     * The Backup Pro system errors 
     * @var array
     */
    protected $system_errors = array();
    
    public function setSystemErrors(array $errors = array())
    {
        $this->system_errors = $errors;
        return $this;
    }
    
    /**
     * Returns any set system errors
     * @return array
     */
    public function getSystemErrors()
    {
        return $this->system_errors;
    }
    
    /**
     * Sends the HTTP Headers defining the request
     * @return void
     */
    public function sendHeaders()
    {
        $parts = explode('\\', get_class($this->platform));
        header('X-BP-Powered-By: Backup Pro '.self::version);
        header('X-BP-Platform: '.end($parts));
    }
    
    /**
     * Returns the data for output and sets the appropriate headers 
     * @param \Nocarrier\Hal $hal
     * @return string
     */
    public function renderOutput(\Nocarrier\Hal $hal)
    {
        $this->sendHeaders();
        if($this->getSystemErrors())
        {
            $system_errors = array();
            foreach($this->getSystemErrors() As $key => $value) {
                $system_errors[$key] = $this->m62Lang($key);
            }
            $hal->setData($hal->getData() + array('_system_errors' => $system_errors));
            
        }
        if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos(strtolower($_SERVER['HTTP_ACCEPT_ENCODING']), 'xml') !== false)
        {
            header('Content-Type: application/hal+xml');
            return $hal->asXml(true);
        }
        else
        {
            header('Content-Type: application/hal+json');
            return $hal->asJson(true);
        }
    }
    
    /**
     * Wrapper to handle error output
     *
     * Note that $detail should be a key for language translation
     * 
     * @param int $code The HTTP response code to send
     * @param string $title The title to display 
     * @param array $errors Any errors to explain what went wrong
     * @param string $detail A human readable explanation of what happened
     * @param string $type A URI resource to deaper explanations on what happened
     */
    public function renderError($code, $title, array $errors = array(), $detail = null, $type = null)
    {
        http_response_code($code);
        
        $problem = $this->getApiProblem($title, $type);
        $problem->setStatus($code);
        $problem->setDetail($detail);
        if($errors)
        {
            $problem['errors'] = $errors;
        }
        
        $this->sendHeaders();
        if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos(strtolower($_SERVER['HTTP_ACCEPT_ENCODING']), 'xml') !== false)
        {
            header('Content-Type: application/problem+xml');
            return $problem->asXml(true);
        }
        else
        {
            header('Content-Type: application/problem+json');
            return $problem->asJson(true);
        }
    }
    
    /**
     * Prepares the Hal object for a Backup collection output
     * @param string $route The API route to access resources from
     * @param array $collection The actual collection
     * @param array $resources Any sub resources (if any)
     * @return \Nocarrier\Hal
     */
    public function prepareBackupCollection($route, array $collection, array $resources = array())
    {
        $data = array();
        foreach($this->backups_output_map AS $key => $value)
        {
            if(isset($collection[$key])){
                $data[$value] = $collection[$key]; 
            }
        }
        
        $hal = $this->getHal($route, $data);
        foreach($resources AS $key => $item)
        {
            $hal = $this->prepareBackupResource($hal, $route, $item);
        }
        return $hal;
    }
    
    /**
     * Prepares the Hal object for a Backup resource output
     * @param \Nocarrier\Hal $hal
     * @param string $route The API route to access the resource from
     * @param array $item The resource data
     * @return \Nocarrier\Hal
     */
    public function prepareBackupResource(\Nocarrier\Hal $hal, $route, array $item)
    {
        $data = array();
        foreach($this->backup_output_map AS $key => $value)
        {
            if(isset($item[$key])){
                $data[$value] = $item[$key];
            }
        }
        
        $resource = $this->getHal($route.'&id='.urlencode($this->m62Encode($item['file_name'])), $data);
        if(isset($item['storage_locations']) && is_array($item['storage_locations']))
        {
            foreach($item['storage_locations'] As $storage)
            {
                $resource = $this->prepareStorageLocationResource($resource, '/storage', $storage);
            }
        }        
        $hal->addResource('backups', $resource);
        return $hal;
    }
    
    /**
     * Prepares the Hal object for a Storage Location Collection
     * @param unknown $route
     * @param array $collection
     * @param array $resources
     * @return \Nocarrier\Hal
     */
    public function prepareStorageLocationCollection($route, array $collection, array $resources = array())
    {
        $hal = $this->getHal($route, $collection);
        foreach($resources AS $key => $item)
        {
            $hal = $this->prepareStorageLocationResource($hal, $route, $item);
        }
        
        return $hal;
    }

    /**
     * Prepares the Hal object for a Backup resource output
     * @param \Nocarrier\Hal $hal
     * @param unknown $route
     * @param array $item
     * @return \Nocarrier\Hal
     */
    public function prepareStorageLocationResource(\Nocarrier\Hal $hal, $route, array $item)
    {
        foreach($this->backup_storage_location_output_map AS $key => $value)
        {
            if(isset($item[$key])){
                $data[$value] = $item[$key];
            }
        }
        
        $resource = $this->getHal($route.'/'.urlencode($item['location_id']), $data);
        $hal->addResource('storage', $resource);
        return $hal;
    }
    
    /**
     * Prepares the Hal object for a Settings Collection
     * @param string $route
     * @param array $collection
     * @param array $resources
     * @return \Nocarrier\Hal
     */
    public function prepareSettingsCollection($route, array $collection, array $resources = array())
    {
        foreach($collection AS $key => $value)
        {
            if(in_array($key, $this->settings_remove_vars)){
                unset($collection[$key]);
            }
        }
        $hal = $this->getHal($route, $collection);
        foreach($resources AS $key => $item)
        {
            $hal = $this->prepareBackupResource($hal, $route, $item);
        }
        return $hal;
    }
    
    public function prepareStorageCollection($route, array $collection, array $resources = array())
    {
        foreach($collection AS $key => $value)
        {
            if(in_array($key, $this->settings_remove_vars)){
                unset($collection[$key]);
            }
        }
        
        $hal = $this->getHal($route, $collection);
        foreach($resources AS $key => $item)
        {
            $hal = $this->prepareBackupResource($hal, $route, $item);
        }
        return $hal;
    }
    
    public function prepareSystemInfoCollection($route, array $collection, array $resources = array())
    {
        $hal = $this->getHal($route, $collection);
        foreach($resources AS $key => $item)
        {
            $hal = $this->prepareBackupResource($hal, $route, $item);
        }
        return $hal;
    }
}