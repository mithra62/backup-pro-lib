<?php
namespace mithra62\BackupPro\Platforms\View;

use mithra62\Platforms\View\Rest as RestView;
use mithra62\BackupPro\Traits\View\Helpers As ViewHelpers;

class Rest extends RestView
{
    use ViewHelpers;

    /**
     * The mapping of resource variables to their output name
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
     * The mapping of resource variables to their output name
     * @var array
     */
    protected $backups_output_map = array(
        'newest_backup_taken_raw' => 'newest_backup_taken_date',
        'oldest_backup_taken_raw' => 'oldest_backup_taken_date',
        'total_space_used_raw' => 'total_space_used',
        'total_backups' => 'total_backups',
        'available_space' => 'available_space',
    );
    
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
    
    public function prepareBackupResource($hal, $route, array $item)
    {
        $data = array();
        foreach($this->backup_output_map AS $key => $value)
        {
            if(isset($item[$key])){
                $data[$value] = $item[$key];
            }
        }
        $resource = $this->getHal($route.'/'.urlencode($this->m62Encode($item['details_file_name'])), $data);
        $resource->addLink('storage', '/customer/bob', array('title' => 'Bob Jones <bob@jones.com>'));
        $hal->addResource('backups', $resource);
        return $hal;
    }
}