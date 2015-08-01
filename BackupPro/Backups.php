<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backups.php
 */
 
namespace mithra62\BackupPro;

/**
 * Backup Pro - Backups Object
 *
 * Contains the methods for handling the backups
 *
 * @package 	BackupPro\Backups
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Backups
{
    /**
     * The File system object
     * @var \mithra62\Files
     */
    private $file = null;
    
    /**
     * An instance of the Storage object
     * @var \mithra62\BackupPro\Backup\Storage
     */
    private $storage = null;
    
    /**
     * An instance of the Details object
     * @var \mithra62\BackupPro\Backup\Details
     */
    private $details = null; 
    
    /**
     * An instance of the Integrity object
     * @var \mithra62\BackupPro\Backup\Integrity
     */
    private $integrity = null;
    
    /**
     * The full system path to where all the backups are stored
     * @var string
     */
    protected $backup_dir = null;
    
    /**
     * The full system path to where database backups are stored
     * @var string
     */
    protected $backup_db_dir = null;
    
    /**
     * The full system path to where file backups are stored
     * @var string
     */
    protected $backup_files_dir = null;
    
    /**
     * An array of the backup meta details
     * @var array
     */
    protected $backup_meta = array();
    
    /**
     * An array of the created Storage Locations
     * @var array
     */
    protected $locations = array();

    /**
     * Set up the dependancies
     * @param \mithra62\Files $file
     */
    public function __construct(\mithra62\Files $file)
    {
        $this->file = $file;
    }
    
    /**
     * Sets the various storage locations where backups are stored
     * @param array $locations
     */
    public function setLocations(array $locations)
    {
        $this->locations = $locations;
        return $this;
    }
    
    /**
     * Returns the various storage locations where backups are stored
     * @return array
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Sets the backup directories using $path as a seed
     * @param string $path
     * @return \mithra62\BackupPro\Backups
     */
    public function setBackupPath($path)
    {
        $this->backup_dir = $path;
        $this->backup_db_dir = $this->getStorage()->getDbBackupDir();
        $this->backup_files_dir = $this->getStorage()->getFileBackupDir();
        return $this;
    }
    
    /**
     * Returns the path to the backup directory
     * @return \mithra62\BackupPro\string
     */
    public function getBackupDir()
    {
        return $this->backup_dir;
    }
    
    /**
     * Returns the path to the database backup directory
     * @return \mithra62\BackupPro\string
     */
    public function getDbBackupDir()
    {
        return $this->backup_db_dir;
    }
    
    /**
     * Returns the path to the files backup directory
     * @return \mithra62\BackupPro\string
     */
    public function getFileBackupDir()
    {
        return $this->backup_files_dir;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Storage
     */
    public function getStorage()
    {
        if( is_null($this->storage) )
        {
            $this->storage = new Backup\Storage($this->backup_dir);
        }
        
        return $this->storage;
    }
    
    /**
     * Returns an instance of the Integrity object
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function getIntegrity()
    {
        if( is_null($this->integrity) )
        {
            $this->integrity = new Backup\Integrity($this->backup_dir);
            $this->integrity->setContext($this);
        }
        
        return $this->integrity;
    }
    
    /**
     * Returns an instance of the Storage object
     * @return \mithra62\BackupPro\Backup\Details
     */
    public function getDetails()
    {
        if( is_null($this->details) )
        {
            $this->details = new Backup\Details();
        }
        
        return $this->details;
    }

    /**
     * Parses the meta details on the backup system for view
     * @param array $backups
     * @return array
     */
    public function getBackupMeta(array $backups = array())
    {
        if(!$this->backup_meta)
        {
            $options = array(
                'newest_backup_taken' => false,
                'newest_backup_taken_raw' => false,
                'oldest_backup_taken' => false,
                'oldest_backup_taken_raw' => false,
                'total_space_used' => $this->file->filesizeFormat(0),
                'total_space_used_raw' => 0,
                'total_backups' => 0
            );
            	
            $return = array();
            $date_range = array('min' => '', 'max' => '');
            foreach($backups AS $type => $backup)
            {
                $return[$type] = $options;
                if(count($backup) == '0')
                {
                    continue;
                }
    
                $temp = $backups;
                $newest_backup = reset($temp[$type]);
                $return[$type]['newest_backup_taken'] = $newest_backup['created_date'];
                $return[$type]['newest_backup_taken_raw'] = $newest_backup['created_date'];
    
                $oldest_backup = end($temp[$type]);
                $return[$type]['oldest_backup_taken'] = $oldest_backup['created_date'];
                $return[$type]['oldest_backup_taken_raw'] = $oldest_backup['created_date'];
    
                $return[$type]['total_backups'] = count($backup);
                $space_used = 0;
                foreach($backup As $file)
                {
                    $space_used = $space_used+$file['compressed_size'];
                    $date_range['max'] = ($file['created_date'] > $date_range['max'] ? $file['created_date'] : $date_range['max']);
                    $date_range['min'] = ($date_range['min'] == '' || $file['created_date'] < $date_range['min'] ? $file['created_date'] : $date_range['min']);
                }
                $return[$type]['total_space_used_raw'] = $space_used;
                $return[$type]['total_space_used'] = $this->file->filesizeFormat($space_used);
            }
            	
            $return['global'] = $options;
            $return['global']['total_backups'] = (int)$return['database']['total_backups']+(int)$return['files']['total_backups'];
            $return['global']['total_space_used'] = $this->file->filesizeFormat( $return['database']['total_space_used_raw']+$return['files']['total_space_used_raw'] );
            $return['global']['total_space_used_raw'] = $return['database']['total_space_used_raw']+$return['files']['total_space_used_raw'];
            	
            $return['global']['oldest_backup_taken'] = ($date_range['min'] != '' ? $date_range['min'] : '');
            $return['global']['oldest_backup_taken_raw'] = $date_range['min'];
            $return['global']['newest_backup_taken'] = ($date_range['max'] != '' ? $date_range['max'] : '');
            $return['global']['newest_backup_taken_raw'] = $date_range['max'];
            	
            $this->backup_meta = $return;
        }
    
        return $this->backup_meta;
    }
    
    /**
     * Inspects the system and returns an array prototype that includes the details per configuration
     * @param bool $auto_threshold Whether to compile for viewing or use
     * @param array $backups The existing backups on the system to use for determining data
     * @return multitype:number
     */
    public function getAvailableSpace($auto_threshold = false, array $backups)
    {
        $options = array(
            'available_space' => 0,
            'available_space_raw' => 0,
            'available_percentage' => 0,
            'max_space' => 0,
        );
    
        $meta = $this->getBackupMeta($backups);
        if($auto_threshold)
        {
            $return = $options;
            $return['available_space_raw'] = $auto_threshold-$meta['global']['total_space_used_raw'];
            $return['available_space'] = $this->file->filesizeFormat($return['available_space_raw']);
            $percentage = ( $meta['global']['total_space_used_raw'] / $auto_threshold ) * 100;
            $return['available_percentage'] = round((100-$percentage), 2);
            $return['max_space'] = $this->file->filesizeFormat($auto_threshold);
        }
        else
        {
            $return = $options;
        }
    
        return $return;
    }
    
    /**
     * Returns all the existing backups on the file system
     * @param array $locations
     * @param array $drivers
     * @return Ambigous <multitype:multitype: , unknown>
     */
    public function getAllBackups(array $locations)
    {
        $data = array('database' => array(), 'files' => array());
        $ignore = array('.svn', 'index.html', 'tmp', '..', '.', '.git', '.meta');
        
        $drivers = $this->getStorage()->getAvailableStorageDrivers();
        $path = $this->getDetails()->getDetailsPath($this->getDbBackupDir());
        if( is_dir($path) )
        {
            $d = dir( $path );
            while ( false !== ($entry = $d->read()) )
            {
                if( !is_dir($this->getDbBackupDir().'/'.$entry) && !in_array($entry, $ignore) )
                {
                    $file_data = $this->getDetails()->getDetails($path.DIRECTORY_SEPARATOR.$entry); 
                    $file_data = $this->getBackupStorageData($file_data, $locations, $drivers);
                    $data['database'][$file_data['created_date']] = $file_data;
                }
            }
            	
            krsort($data['database'], SORT_NUMERIC);
        }

        $path = $this->getDetails()->getDetailsPath($this->getFileBackupDir());
        if( is_dir($path) )
        {
            $d = dir( $path );
            while (false !== ($entry = $d->read()))
            {
                if( !is_dir($this->getFileBackupDir().'/'.$entry) && !in_array($entry, $ignore) )
                {
                    $file_data = $this->getDetails()->getDetails($path.DIRECTORY_SEPARATOR.$entry); 
                    $file_data = $this->getBackupStorageData($file_data, $locations, $drivers);
                    $data['files'][$file_data['created_date']] = $file_data;
                }
            }
            krsort($data['files'], SORT_NUMERIC);
        }
    
        return $data;
    }
    
    /**
     * Merges up the storage data into the backup hash
     * @param array $file_data
     * @param array $locations
     * @param array $drivers
     * @return array
     */
    public function getBackupStorageData(array $file_data, array $locations, array $drivers)
    {
        foreach($file_data['storage'] AS $storage)
        {
            foreach($locations AS $location_id => $location)
            {
                if( $location_id == $storage )
                {
                    if( !empty($drivers[$location['storage_location_driver']]['obj']) && $drivers[$location['storage_location_driver']]['obj'] instanceof Backup\Storage\StorageInterface )
                    {
                        $file_data['storage_locations'][$storage] = $location;
                        $file_data['storage_locations'][$storage]['icon'] = $drivers[$location['storage_location_driver']]['obj']->getIcon();
                        $file_data['storage_locations'][$storage]['download_url'] = $drivers[$location['storage_location_driver']]['obj']->getDownloadUrl();
                        $file_data['storage_locations'][$storage]['obj'] = $drivers[$location['storage_location_driver']]['obj'];
                        continue;
                    }
                }
            }
        }
        
        return $file_data;
    }
    
    /**
     * Returns the information about a backup
     * @param string $backup The full path to the backup we want details on
     * @return Ambigous <multitype:, unknown>
     */
    public function getBackupData($backup)
    {
        $path_details = pathinfo($backup);
        $details_dir = $this->getDetails()->getDetailsPath($path_details['dirname']);
        $backup_path = $details_dir.DIRECTORY_SEPARATOR.$path_details['basename'];
        $backup_data = $this->getDetails()->getDetails($backup_path);
        $locations = $this->getLocations();
        
        foreach($locations AS $location)
        {
            if( isset($backup_data['storage'][$location['location_id']]) )
            {
                $location['obj'] = $this->getStorage()->getDriver($location['storage_location_driver'])->setSettings($location);
                $backup_data['storage_locations'][] = $location;
            }
        }
        
        return $backup_data;
    }
    
    /**
     * Removes an array of backups
     * @param array $backups
     * @return boolean
     */
    public function removeBackups(array $backups)
    {
        foreach($backups AS $backup)
        {
            $this->getStorage()->remove($backup, $this->getDetails());
        }
        
        return true;
    }
}