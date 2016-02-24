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

/**
 * Backup Pro - REST View abstraction
 *
 * Contains the REST specific view helpers
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Rest extends RestView
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
    
    protected $backup_storage_location_output_map = array(
        'storage_location_name' => 'storage_location_name',
        'location_id' => 'storage_location_id',
        'storage_location_driver' => 'storage_location_driver',
        'storage_location_file_use' => 'storage_location_db_use',
        'storage_location_include_prune' => 'storage_location_include_prune',
        'storage_location_create_date' => 'storage_location_create_date',
    );
    
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
        
        $resource = $this->getHal($route.'?id='.urlencode($this->m62Encode($item['details_file_name'])), $data);
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
    
    public function prepareStorageLocationCollection($route, array $collection, array $resources = array())
    {
        $hal = $this->getHal($route);
        foreach($resources AS $key => $item)
        {
            $hal = $this->prepareBackupResource($hal, $route, $item);
        }
        return $hal;
    }
    
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
}