<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Locations.php
 */
namespace mithra62\BackupPro\Backup\Storage;

/**
 * Backup Pro - Backup Storage Location Object
 *
 * Wrapper for handling storage location CRUD of backups
 *
 * @package Backup\Storage
 * @author Eric Lamb <eric@mithra62.com>
 */
class Locations
{

    /**
     * The Storage object
     * 
     * @var \mithra62\BackupPro\Backup\Storage
     */
    private $storage = null;

    /**
     * The Settings object
     * 
     * @var \mithra62\Settings
     */
    private $setting = null;

    /**
     * Sets the Storage object for use
     * 
     * @param \mithra62\BackupPro\Backup\Storage $storage
     *            The Storage object
     * @return \mithra62\BackupPro\Backup\Storage\Locations
     */
    public function setContext(\mithra62\BackupPro\Backup\Storage $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * Returns an instance of the Storage object
     * 
     * @return \mithra62\BackupPro\Backup\Storage
     */
    public function getContext()
    {
        return $this->storage;
    }

    /**
     * Returns the Setting object
     * 
     * @return \mithra62\Settings
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * Sets the Setting object
     * 
     * @param \mithra62\Settings $setting            
     * @return \mithra62\BackupPro\Backup\Storage\Locations
     */
    public function setSetting(\mithra62\Settings $setting)
    {
        $this->setting = $setting;
        return $this;
    }

    /**
     * Parses the $backups array for the backups stored at the $location_id
     * 
     * @param string $location_id
     *            The storage ID for the location we want to check out
     * @param array $backups
     *            The backups to weed through
     * @return Ambigous <multitype:, unknown>
     */
    public function getLocationBackups($location_id, array $backups)
    {
        $return = array();
        foreach ($backups as $type => $backup) {
            foreach ($backup as $date_taken => $backup_data) {
                if (in_array($location_id, $backup_data['storage'])) {
                    $return[$type][$date_taken] = $backup_data;
                }
            }
        }
        
        return $return;
    }

    /**
     * Creates a Location storage item in the Settings data
     * 
     * @param \mithra62\Settings $settings
     *            The Settings object to save the Location data
     * @param string $driver
     *            The name of the driver we're using
     * @param array $data            
     */
    public function create($driver, array $data)
    {
        $obj = $this->getContext()->getDriver($driver);
        $_settings = $this->getSetting()->get();
        $update = array();
        $location_id = md5(microtime(true));
        $_settings['storage_details'][$location_id] = $obj->prepCreate($data);
        $_settings['storage_details'][$location_id]['location_id'] = $location_id;
        $update['storage_details'] = $_settings['storage_details'];
        return $this->getSetting()->update($update);
    }

    /**
     * Updates a Location storage item in the Settings data
     * 
     * @param \mithra62\Settings $settings
     *            The Settings object to save the Location data
     * @param string $storage_id
     *            The storage location we're updating
     * @param array $data
     *            The name of the driver we're using
     * @return boolean
     */
    public function update($storage_id, array $data)
    {
        $_settings = $this->getSetting()->get();
        if (! empty($_settings['storage_details'][$storage_id])) {
            $obj = $this->getContext()->getDriver($_settings['storage_details'][$storage_id]['storage_location_driver']);
            $_settings['storage_details'][$storage_id] = array_merge($_settings['storage_details'][$storage_id], $obj->prepUpdate($data));
            
            $update = array();
            $_settings['storage_details'][$storage_id] = array_merge($_settings['storage_details'][$storage_id], $obj->prepUpdate($data));
            $update['storage_details'] = $_settings['storage_details'];
            return $this->getSetting()->update($update);
        }
        
        return false;
    }

    /**
     *
     * @param \mithra62\Settings $settings
     *            The Settings object to save the Location data
     * @param string $storage_id            
     * @param array $data
     *            The name of the driver we're using
     * @return boolean
     */
    
    /**
     * Removes a Location storage item in the Settings data
     * 
     * @param string $storage_id
     *            The storage location we're removing
     * @param array $data
     *            The configuration data to determine how deep we want to go
     * @param array $backups            
     * @return boolean
     */
    public function remove($storage_id, array $data, array $backups = array())
    {
        $_settings = $this->getSetting()->get();
        if (! empty($_settings['storage_details'][$storage_id])) {
            $obj = $this->getContext()
                ->getDriver($_settings['storage_details'][$storage_id]['storage_location_driver'])
                ->setSettings($_settings['storage_details'][$storage_id]);
            if (! empty($data['remove_remote_files']) && $data['remove_remote_files'] == '1') {
                $obj->clearFiles();
            }
            
            // $backups = $this->getLocationBackups($storage_id, $backups);
            
            unset($_settings['storage_details'][$storage_id]);
            $update = array();
            $update['storage_details'] = $_settings['storage_details'];
            return $this->getSetting()->update($update);
        }
        
        return false;
    }
}