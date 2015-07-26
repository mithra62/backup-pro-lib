<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Local.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Backup\Storage\AbstractStorage;
use mithra62\BackupPro\Remote;
use mithra62\Remote\Local AS m62Local;

/**
 * Backup Pro - Local Storage Object
 *
 * Driver for storing files locally on the file system
 *
 * @package 	Backup\Storage\Driver
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Local extends AbstractStorage 
{
    /**
     * The name of the Storage Driver
     * @var string
     */
    protected $name = 'backup_pro_local_storage_driver_name';
    
    /**
     * A description of the Storage Driver
     * @var string
     */
    protected $desc = 'backup_pro_local_storage_driver_description';
    
    /**
     * The settings the Storage Driver provides
     * @var string
     */
    protected $settings = array(
        'backup_store_location' => ''
    );
    
    /**
     * Boolean to determine whether the auto prune setting should apply to the Storage Driver
     * @var boolean
     */
    protected $prune_include = true;
    
    /**
     * The file name, sans .png extention, for the icon
     * @var string
     */
    protected $icon_name = 'local';
    
    /**
     * A slug for use; must be unique
     * @var string
     */
    protected $short_name = 'local';

    /**
     * Whether the selected Driver can supply download URLs
     * @var bool
     */
    protected $can_download = true;
    
    /**
     * Whether the selected Driver can allow for restores
     * @var bool
     */
    protected $can_restore = true;    
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getDownloadUrl()
     */
    public function getDownloadUrl()
    {
        
    }
    
    /**
     * Removes a file from the storage service
     * @param string $file_name The path to the file
     * @param string $backup_type The backup type to remove
     * @return bool
     */
    public function removeFile($file_name, $backup_type = 'database')
    {
        $filesystem = new Remote( new m62Local($this->settings['backup_store_location']) );
        $path = $backup_type.DIRECTORY_SEPARATOR.$file_name;
        if( $filesystem->has($path) )
        {
            return $filesystem->delete($path);
        }
    }
    
    /**
     * Removes all the files from within the path
     * @param string $path The path to the file
     * @return bool
     */
    public function removeFiles($path)
    {
        
    }
    
    /**
     * Removes the given directory path
     * @param string $path The path to the file
     */
    public function removeDir($path)
    {
        
    }
    
    /**
     * Returns the path to the file
     * @param string $file_name The full path to the backup
     * @param string $backup_type The type of backup, either database or files
     */
    public function getFilePath($file_name, $backup_type = 'database')
    {
        $filesystem = new Remote( new m62Local($this->settings['backup_store_location']) );
        $path = $backup_type.DIRECTORY_SEPARATOR.$file_name;
        if( $filesystem->has($path) )
        {
            return $this->settings['backup_store_location'].DIRECTORY_SEPARATOR.$path;
        }
    }
    
    /**
     * Removes all the files and directories from the given path
     * @return void
     */
    public function clearFiles()
    {
        //listContents
        $filesystem = new Remote( new m62Local($this->settings['backup_store_location']) );
        $contents = $filesystem->listContents();
        if( $contents && is_array($contents) )
        {
            foreach($contents AS $file)
            {
                if($file['type'] == 'dir')
                {
                    $filesystem->deleteDir($file['path']);
                }
                else
                {
                    $filesystem->delete($file['path']);
                }
            }
        }
    }
    
    /**
     * Should create a file using the Driver service
     * @param string $file The path to the file to create on the service
     * @param string $type
     * @return bool
     */
    public function createFile($file, $type = 'database')
    {
        $filesystem = new Remote( new m62Local($this->settings['backup_store_location']) );
        $filesystem->checkBackupDirs();
        
        $file_name = basename($file);
        $new_path = $this->settings['backup_store_location'].DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.$file_name;
        if( !file_exists($new_path) )
        {
            return copy($file, $new_path);
        }
    }

    /**
     * Should validate the Driver settings using the \mithra62\Validate object
     * @param \mithra62\Validate $validate
     * @param array $settings
     * @param array $drivers
     * @return \mithra62\Validate
     */
    public function validateSettings(\mithra62\Validate $validate, array $settings, array $drivers = array())
    {
        $locations = $this->getExistingStorageLocations();
        $ignore = array();
        foreach($locations AS $location_id => $location)
        {
            if( $this->short_name == $location['storage_location_driver'] )
            {
                if( empty($settings['location_id']) )
                {
                    //same driver so ensure no duplicate locations
                    $ignore[] = $location['backup_store_location'];
                }
                else 
                {
                    if( $settings['location_id'] != $location_id )
                    {
                        $ignore[] = $location['backup_store_location'];
                    }
                }
            }
        }
        
        if( $ignore )
        {
            $validate->rule('notIn', 'backup_store_location', $ignore)->message('{field} is already setup with another Storage Location');
        }
        
        $validate->rule('required', 'backup_store_location')->message('{field} is required');
        $validate->rule('dir', 'backup_store_location')->message('{field} has to be a directory');
        $validate->rule('writable', 'backup_store_location')->message('{field} has to be writable');
        $validate->rule('readable', 'backup_store_location')->message('{field} has to be readable');
        return $validate;
    }
}