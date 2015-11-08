<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage.php
 */
 
namespace mithra62\BackupPro\Backup;

use mithra62\BackupPro\Exceptions\Backup\StorageException;

/**
 * Backup Pro - Backup Storage Object
 *
 * Wrapper for handling storage of backups
 *
 * @package 	Backup\Storage
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Storage
{ 
    
    /**
     * String to seperate database filenames between parts
     * @var string
     */
    protected $name_sep = '@@';
    
    /**
     * The path to where archives are stored and worked on
     * @var string
     */
    protected $backup_dir = false;
    
    /**
     * The available Storage Drivers
     * @var array
     */
    protected $drivers = array();
    
    /**
     * The Locations object
     * @var Storage\Locations
     */
    protected $locations = null;
    
    /**
     * The Services array
     * @var array
     */
    private $services = array();
    

    /**
     * The Cleanup object
     * @var Storage\Cleanup
     */
    private $cleanup = null;    
    
    /**
     * Sets it up
     * @param string $path The full path to where archives are stored
     */
    public function __construct($path = null)
    {
        if( !is_null($path) )
        {
            $this->backup_dir = $path;
        }
    }
    
    /**
     * Returns the path to the backup directory
     * @return \mithra62\BackupPro\Backup\string
     */
    public function getBackupDir()
    {
        return $this->backup_dir;
    }
    
    /**
     * Returns the path to where database backups should be stored/processed
     * @return string
     */
    public function getDbBackupDir()
    {
        return $this->backup_dir.DIRECTORY_SEPARATOR .'database';
    }
    
    /**
     * Returns the path to where file backups should be stored/processed
     * @return string
     */
    public function getFileBackupDir()
    {
        return $this->backup_dir.DIRECTORY_SEPARATOR .'files';
    }
    
    /**
     * Returns the full path to the file backup archive file
     * @param string $name
     * @return string
     */
    public function getFileBackupNamePath($name)
    {
        return $this->getFileBackupDir().DIRECTORY_SEPARATOR.$name;
    }
    
    /**
     * Returns the full path to the database backup archive file
     * @param string $name
     * @return string
     */
    public function getDbBackupNamePath($name)
    {
        return $this->getDbBackupDir().DIRECTORY_SEPARATOR.$name;
    }
    
    /**
     * Sets the backup storage directory
     * @param unknown $path
     * @return \mithra62\BackupPro\Backup\Storage
     */
    public function setBackupDir($path)
    {
        $this->backup_dir = $path;
        return $this;
    }
    
    /**
     * Creates the filename to use for the database backup
     * @param string $db_backup_method
     * @param string $db_name
     * @return string
     */
    public function makeDbFilename($db_backup_method = false, $db_name = false)
    {
        $tail = '';
        if($db_backup_method == 'mysqldump')
        {
            $tail = $db_backup_method.$this->name_sep;
        }
        	
        return date('U').$this->name_sep.$tail.$db_name.'.sql';
    }
    
    /**
     * Creates the full path to store the backup for the filesystem
     * @return string
     */
    public function makeFileFilename()
    {
        return date('U');
    }
    
    /**
     * Returns the details for the configured Storage objects on the system
     * @throws StorageException
     * @return multitype:NULL
     * @deprecated Use self::getAvailableStorageDrivers() instead
     */
    public function getAvailableStorageOptions()
    {
        return $this->getAvailableStorageDrivers();
    }
    
    /**
     * Returns the details for the configured Storage objects on the system
     * @throws StorageException
     * @return array
     */
    public function getAvailableStorageDrivers()
    {
        if( !$this->drivers )
        {
            $old_cwd = getcwd();
            chdir(dirname(__FILE__));
            $path = './Storage/Drivers';
            if( !is_dir($path) )
            {
                throw new StorageException("Storage Directory ".$path." isn't a directory...");
            }
        
            $d = dir($path);
            $storage_options = array();
            while (false !== ($entry = $d->read()))
            {
                $name = ucfirst(str_replace('.php', '', $entry));
                $class = "\\mithra62\\BackupPro\\Backup\\Storage\\Drivers\\".$name;
                if( class_exists($class) )
                {
                    $obj = new $class;
                    if( $obj instanceof Storage\StorageInterface )
                    {
                        $storage_options[$obj->getShortName()] = $obj->getDetails();
                        $storage_options[$obj->getShortName()]['class'] = $class;
                        $storage_options[$obj->getShortName()]['obj'] = $obj;
                    }
                }
            }
        
            $d->close();
            chdir($old_cwd);
        
            $this->drivers = $storage_options;
        }
        
        return $this->drivers;        
    }
    
    /**
     * Validates the given driver's data
     * @param \mithra62\Validate $validate
     * @param string $driver The name of the driver
     * @param array $data The data the driver is validating
     * @param array $locations The existing locations we may want to validate against
     * @return array
     */
    public function validateDriver(\mithra62\Validate $validate, $driver, array $data, array $locations = array())
    {
        $storage_drivers = $this->getAvailableStorageOptions();
        $validate->rule('required', 'storage_location_name')->message('{field} is required');
        
        //pass to the engine to validate what it needs
        if(isset($storage_drivers[$driver]) && $storage_drivers[$driver]['obj'] instanceof Storage\StorageInterface )
        {
            $validate = $storage_drivers[$driver]['obj']->setExistingStorageLocations($locations)->validateSettings($validate, $data);
        }
        
        //now we ahve to make sure we always have at least 1 location active
        if( count($locations) <= 1 && $data['storage_location_status'] != '1' )
        {
            $validate->rule('false', 'storage_location_status')->message('{field} is required unless you have more than 1 Storage Location');
        }

        //now we ahve to make sure we always have at least 1 database location active
        if( count($locations) <= 1 && $data['storage_location_file_use'] != '1' )
        {
            $validate->rule('false', 'storage_location_file_use')->message('{field} is required unless you have more than 1 Storage Location');
        }
        
        //now we ahve to make sure we always have at least 1 file location active
        if( count($locations) <= 1 && $data['storage_location_db_use'] != '1' )
        {
            $validate->rule('false', 'storage_location_db_use')->message('{field} is required unless you have more than 1 Storage Location');
        }
        
        $errors = array();
        if( !$validate->val($data) )
        {
            $errors = $validate->getErrorMessages();
        }
        
        return $errors;    
    }
    
    /**
     * Returns an instance of the given driver
     * @param string $driver The short name of the Storage Driver
     */
    public function getDriver($driver)
    {
        $storage_drivers = $this->getAvailableStorageOptions();
        if(isset($storage_drivers[$driver]) && $storage_drivers[$driver]['obj'] instanceof Storage\StorageInterface )
        {
            return $storage_drivers[$driver]['obj'];
        } 
        
        throw new StorageException("Storage Driver ".$driver." doesn't exist...");
    }
    
    /**
     * Processes the File Transfer and delegates to the Storage Drivers
     * @param string $file The full path to the archive file
     * @param array $locations An array of existing Locations to determine what Drivers to use and send the file to where
     * @param string $type
     * @param Details $details
     * @param Progress $progress
     */
    public function save($file, array $locations, $type = 'database', Details $details = null, Progress $progress = null)
    {
        $path_parts = pathinfo($file);
        $details_path = $path_parts['dirname'];
        $file_name = $path_parts['basename'];
        $stored_location = array();
        $can_download = $can_restore = 0;
        foreach($locations AS $location_id => $location)
        {
            if( $location['storage_location_status'] != '1')
            {
                continue; //storage location is disabled so don't use it
            }
            
            $driver = $this->getDriver($location['storage_location_driver']);
            $driver->setServices( $this->getServices() );
            if( !is_null($details) )
            {
                $driver->setBackupDetails($details);
            }
            
            //we don't use this driver with database backups
            if( $type == 'database' && $location['storage_location_db_use'] != '1')
            {
                continue;
            }

            //we don't use this driver with file backups
            if( $type == 'file' && $location['storage_location_file_use'] != '1')
            {
                continue;
            }
            
            if( !is_null($progress) )
            {
                //@todo add progress updating
            }
            
            if( $driver->setSettings($location)->createFile($file, $type) )
            {
                $stored_location[$location_id] = $location_id;
                if( !is_null($progress) )
                {
                    //@todo add progress updating
                }
            }
            
            if( $driver->canDownload() )
            {
                $can_download = 1;
            }
            
            if( $driver->canRestore() )
            {
                $can_restore = 1;
            }
        }
        
        if( !is_null($details) )
        {
            $base_details = array();
            $base_details['storage'] = $stored_location;
            $base_details['can_download'] = $can_download;
            $base_details['can_restore'] = $can_restore;
            $details->addDetails($file_name, $details_path, $base_details);
        }        
    }
    
    /**
     * Removes a single backup
     * @param array $backup The backup details array we want to remove
     * @param Details $details The Details object
     * @param bool $cleanup Whether this is a cleanup removal, in which case the "storage_location_include_prune" setting comes into play
     * @return boolean
     */
    public function remove(array $backup, Details $details = null, $cleanup = false)
    {
        if( isset($backup['storage_locations']) && is_array($backup['storage_locations']) )
        {
            foreach( $backup['storage_locations'] AS $storage_id => $storage)
            {
                if( isset($storage['obj']) && $storage['obj'] instanceof Storage\StorageInterface)
                {
                    $remove = true;
                    $settings = $storage; unset($settings['obj']);
                    if( $cleanup && $settings['storage_location_include_prune'] != '1' )
                    {
                        $remove = false; //we're doing a prune remove so only want to do it if configured to do so
                    }
                    
                    if( $remove )
                    {
                        $storage['obj']->setSettings($settings)->removeFile($backup['file_name'], $backup['backup_type']);
                    }
                }
            }
        }
        
        if( !is_null($details) )
        {
            $path = ( $backup['backup_type'] == 'database' ? $this->getDbBackupDir() : $this->getFileBackupDir() ); 
            $details->removeDetailsFile($backup['file_name'], $path);
        }
        
        return true;
    }    
    
    /**
     * Takes the backup data and returns how much space is taken up by them all
     * @param array $backups
     * @return number
     */
    public function getSpaceUsed(array $backups)
    {
        $amount = 0;
        foreach($backups AS $type => $_backup)
        {
            if(is_array($_backup))
            {
                foreach($_backup AS $backup)
                {
                    if(isset($backup['compressed_size']))
                    {
                        $amount = ($amount+(int)$backup['compressed_size']);
                    }
                }
            }
        }
    
        if($amount > 0)
        {
            return $amount;
        }
    } 
    
    /**
     * Returns an instance of the Locations object
     * @return \mithra62\BackupPro\Backup\Storage\Locations
     */
    public function getLocations()
    {
        if( is_null($this->locations) )
        {
            $locations = new Storage\Locations();
            $locations->setContext($this);
            $this->locations = $locations;
        }
        
        return $this->locations;
    }

    /**
     * Returns an instace of the Backup\Cleanup object
     * @return \Backup\Cleanup
     */
    public function getCleanup()
    {
        if( is_null($this->cleanup) )
        {
            $this->cleanup = new Storage\Cleanup();
            $this->cleanup->setContext($this);
        }
    
        return $this->cleanup;
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
}