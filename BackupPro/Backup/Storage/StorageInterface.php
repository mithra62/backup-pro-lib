<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/StorageInterface.php
 */

namespace mithra62\BackupPro\Backup\Storage;

/**
 * Backup Pro - Storage Driver Interface
 *
 * Outlines the various methods every Storage Driver for Backup Pro should use
 *
 * @package 	Backup\Storage
 * @author		Eric Lamb <eric@mithra62.com>
 */
interface StorageInterface
{
    /**
     * Should return a boolean whether a driver has a configuration view
     * @return bool
     */
    public function hasSettingsView();
    
    /**
     * Should return a boolean whether the driver can provide the file for download
     * @return bool
     */
    public function canDownload();
    
    /**
     * Should return a boolean whether the driver can provide the file for restoration
     * @return bool
     */    
    public function canRestore();
    
    /**
     * Returns the icon file name 
     * @return string
     */
    public function getIcon();
    
    /**
     * Returns the name of the Driver
     * @return string
     */
    public function getName();
    
    /**
     * Returns the URL to download a backup from the Driver service
     * @return string
     */
    public function getDownloadUrl();
    
    /**
     * Should remove a file from the remove service
     * @param string $file The path to the file
     * @param string $type
     */
    public function removeFile($file, $type = 'database');
    
    /**
     * Should remove all the files from within the path
     * @param string $path The path to the file
     */
    public function removeFiles($path);
    
    /**
     * Should remove the given directory path
     * @param string $path The path to the file
     */
    public function removeDir($path);
    
    /**
     * Should create a file using the Driver service
     * @param string $file The path to the file to create on the service
     * @param string $type
     * @return bool
     */
    public function createFile($file, $type = 'database');
    
    /**
     * Should remove all the files and directories from the given path
     */
    public function clearFiles();
    
    /**
     * Should validate the Driver settings using the \mithra62\Validate object
     * @param \mithra62\Validate $validate
     * @param array $settings
     * @param array $drivers
     * @return \mithra62\Validate
     */
    public function validateSettings(\mithra62\Validate $validate, array $settings, array $drivers = array());
    
    /**
     * Should return an array with the details for the Driver
     * @return array
     */
    public function getDetails();
    
    /**
     * Should return the short name for the driver
     * @return string
     */
    public function getShortName();
    
    /**
     * Should return an array of settings for configuring the driver
     * @return array
     */
    public function getSettings();

    /**
     * Should return an the language key to the Storage Driver description
     * @return string
     */
    public function getDesc();
    
    /**
     * Prepares the storage data for creation
     * @param array $data
     * @return array
     */
    public function prepCreate(array $data);
    
    /**
     * Prepares the storage data for update
     * @param array $data
     * @return array
     */
    public function prepUpdate(array $data);
    
    /**
     * Sets the Storage Driver settings
     * @param array $settings
     */
    public function setSettings(array $settings);
    
    /**
     * Sets the existing Storage Locations setup
     * @param array $locations
     * @param string $ignore_location_id
     */
    public function setExistingStorageLocations(array $locations, $ignore_location_id = false);
    
    /**
     * Returns the existing storage Locations
     */
    public function getExistingStorageLocations();
    
    /**
     * Returns the path to the file
     * @param string $file_name The full path to the backup
     * @param string $backup_type The type of backup, either database or files
     */
    public function getFilePath($file_name, $backup_type = 'database');
}