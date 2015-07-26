<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/AbsrtactStorage.php
 */
namespace mithra62\BackupPro\Backup\Storage;

use mithra62\BackupPro\Backup\Details;
use mithra62\BackupPro\Exceptions\Backup\StorageException;

/**
 * Backup Pro - Storage Driver Abstract
 *
 * Contains some helper methods for the Storage Drivers to inherit
 *
 * @package 	Backup\Storage
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class AbstractStorage implements StorageInterface 
{
    /**
     * The name of the Storage Driver
     * @var string
     */
    protected $name = false;
    
    /**
     * A description of the Storage Driver
     * @var string
     */
    protected $desc = false;
    
    /**
     * The needed settings for the Driver
     * @var array
     */
    protected $settings = array();
    
    /**
     * Flag to determine whether the driver should be included with Pruning
     * @var bool
     */
    protected $prune_include = false;
    
    /**
     * The base file name for the icon stored in teh images/storage directory
     * @var string
     */
    protected $icon_name = false;
    
    /**
     * A human readable shortname for the driver
     * @var string
     */
    protected $short_name = false;
    
    /**
     * Whether the selected Driver can supply download URLs
     * @var bool
     */
    protected $can_download = false;
    
    /**
     * Whether the selected Driver can allow for restores
     * @var bool
     */
    protected $can_restore = false;
    
    /**
     * Whether the selected Driver will have any Settings to present 
     * @var bool
     */
    protected $has_setting_view = true;
    
    /**
     * The setup storage locations
     * @var array
     */
    protected $existing_storage_locations = array();
    
    /**
     * The ID of the current storage location the driver is validating
     * @var string
     */
    protected $val_ignore_location_id = false;
    
    /**
     * The base array structure each Storage array will need to be in
     * @var array
     */
    private $settings_prototype = array(
        'storage_location_name' => '', //duh
        'location_id' => '', //duh
        'storage_location_status' => '1', //Whether the driver should be used 
        'storage_location_driver' => '', //The driver the storage settings are for
        'storage_location_file_use' => '0', //Whether file backups should be stored 
        'storage_location_db_use' => '0', //Whether dataabse backups should be stored
        'storage_location_include_prune' => '1', //Whether to include in the automatic pruning
        'storage_location_create_date' => '', //The date the backup was created
    );
    
    /**
     * The Details object
     * @var Details
     */
    private $details_obj = null;
    
    /**
     * The Services array
     * @var array
     */
    private $services = array();
    
    /**
     * Returns the settings prototype array
     * @return array
     */
    protected function getSettingsPrototype()
    {
        $this->settings_prototype['storage_location_driver'] = $this->short_name;
        return $this->settings_prototype;
    }
    
    /**
     * Returns the Details object
     */
    protected function getBackupDetails()
    {
        return $this->details_obj;
    }
    
    /**
     * Sets the Details object
     * @param Details $details
     * @return \mithra62\BackupPro\Backup\Storage\AbstractStorage
     */
    public function setBackupDetails(Details $details)
    {
        $this->details_obj = $details;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getExistingStorageLocations()
     */
    public function getExistingStorageLocations()
    {
        return $this->existing_storage_locations;
    }
    
    /**
     * Sets the existing Storage Locations setup
     * @param array $locations
     * @param string $ignore_location_id
     * @return \mithra62\BackupPro\Backup\Storage\AbstractStorage
     */
    public function setExistingStorageLocations(array $locations, $ignore_location_id = false)
    {
        $this->existing_storage_locations = $locations;
        $this->val_ignore_location_id = $ignore_location_id;
        return $this;
    }
    
    /**
     * Returns whether a driver has a configuration view
     * @return bool
     */
    public function hasSettingsView()
    {
        return $this->has_setting_view;
    }
    
    /**
     * Returns whether the driver can provide the file for download
     * @return bool
     */
    public function canDownload()
    {
        return $this->can_download;
    }
    
    /**
     * Returns whether the driver can provide the file for restoration
     * @return bool
     */    
    public function canRestore()
    {
        return $this->can_restore;
    }
    
    /**
     * Returns the path to the file
     * @param string $file_name The full path to the backup
     * @param string $backup_type The type of backup, either database or files
     */
    public function getFilePath($file_name, $backup_type = 'database')
    {
        throw new StorageException('Not implemented!');
    }
    
    /**
     * Returns the icon file name 
     * @return string
     */
    public function getIcon()
    {
        return $this->icon_name;
    }
    
    /**
     * Returns the name of the Driver
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Returns an array with the details for the Driver
     * @return array
     */
    public function getDetails()
    {
        return array(
            'name' => $this->getName(),
            'short_name' => $this->getShortName(),
            'desc' => $this->getDesc(),
            'icon' => $this->getIcon(),
            'settings' => $this->getSettings()
        );
    }
    
    /**
     * Should return the short name for the driver
     * @return string
     */
    public function getShortName()
    {
        return $this->short_name;
    }
    
    /**
     * Should return an array of settings for configuring the driver
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
    
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::setSettings()
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }
    
    /**
     * Returns an the language key to the Storage Driver description
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }
    
    /**
     * Prepares the storage data for creation
     * @param array $data
     * @return array
     */
    public function prepCreate(array $data)
    {
        $data['storage_location_create_date'] = date('U');
        return array_merge($this->getSettingsPrototype(), $data);
    }
    
    /**
     * Prepares the storage data for update
     * @param array $data
     * @return array
     */
    public function prepUpdate(array $data)
    {
        return $data;
    }
    
    /**
     * Sets the Services array
     * @param array $services
     */
    public function setServices(\Pimple\Container $services)
    {
        $this->services = $services;
        return $this;
    }
    
    /**
     * Returns the Serivices array
     */
    protected function getServices()
    {
        return $this->services;
    }

    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getDownloadUrl()
     */
    public function getDownloadUrl()
    {
        return false;
    }    
} 