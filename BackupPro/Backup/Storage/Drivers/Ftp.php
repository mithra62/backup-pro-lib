<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Ftp.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Backup\Storage\AbstractStorage;
use mithra62\BackupPro\Exceptions\Backup\StorageException;
use mithra62\BackupPro\Remote;
use mithra62\Remote\Ftp as m62Ftp;

/**
 * Backup Pro - FTP Storage Object
 *
 * Driver for storing files on a remote FTP server
 *
 * @package Backup\Storage\Driver
 * @author Eric Lamb <eric@mithra62.com>
 */
class Ftp extends AbstractStorage
{

    /**
     * The name of the Storage Driver
     * 
     * @var string
     */
    protected $name = 'backup_pro_ftp_storage_driver_name';

    /**
     * A description of the Storage Driver
     * 
     * @var string
     */
    protected $desc = 'backup_pro_ftp_storage_driver_description';

    /**
     * The settings the Storage Driver provides
     * 
     * @var string
     */
    protected $settings = array(
        'ftp_hostname' => '',
        'ftp_username' => '',
        'ftp_password' => '',
        'ftp_debug' => '0',
        'ftp_port' => '21',
        'ftp_passive' => '0',
        'ftp_store_location' => '',
        'ftp_ssl' => '0',
        'ftp_timeout' => 90
    );

    /**
     * Boolean to determine whether the auto prune setting should apply to the Storage Driver
     * 
     * @var boolean
     */
    protected $prune_include = true;

    /**
     * The file name, sans .
     * png extention, for the icon
     * 
     * @var string
     */
    protected $icon_name = 'ftp';

    /**
     * A slug for use; must be unique
     * 
     * @var string
     */
    protected $short_name = 'ftp';

    /**
     * Whether the selected Driver can supply download URLs
     * 
     * @var bool
     */
    protected $can_download = false;

    /**
     * Whether the selected Driver can allow for restores
     * 
     * @var bool
     */
    protected $can_restore = false;

    /**
     * Removes a file from the remove service
     * 
     * @param string $file_name
     *            The path to the file
     * @param string $backup_type
     *            the backup type (file or database) we're processing
     * @return bool
     */
    public function removeFile($file_name, $backup_type = 'database')
    {
        $filesystem = $this->getFilesystem();
        $path = $backup_type . '/' . $file_name;
        if ($filesystem->has($path)) {
            return $filesystem->delete($path);
        }
    }

    /**
     * NOT USED!!
     * 
     * @param string $path
     *            The path to the file
     * @return bool
     */
    public function removeFiles($path)
    {
        return true;
    }

    /**
     * NOT USED!!
     * 
     * @param string $path
     *            The path to the file
     */
    public function removeDir($path)
    {
        return true;
    }

    /**
     * NOT USED!!
     * 
     * @param string $file_name
     *            The full path to the backup
     * @param string $backup_type
     *            The type of backup, either database or files
     */
    public function getFilePath($file_name, $backup_type = 'database')
    {
        throw new StorageException('Unused');
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::clearFiles($path)
     */
    public function clearFiles()
    {
        return true;
    }

    /**
     * Should create a file using the Driver service
     * 
     * @param string $file
     *            The path to the file to create on the service
     * @param string $type            
     * @return bool
     */
    public function createFile($file, $type = 'database')
    {
        $filesystem = $this->getFilesystem();
        $stream = fopen($file, 'r+');
        $file_name = basename($file);
        return $filesystem->writeStream($type . '/' . $file_name, $stream);
    }

    /**
     * Should validate the Driver settings using the \mithra62\Validate object
     * 
     * @param \mithra62\Validate $validate            
     * @param array $settings            
     * @param array $drivers            
     * @return \mithra62\Validate
     */
    public function validateSettings(\JaegerApp\Validate $validate, array $settings, array $drivers = array())
    {
        $validate->rule('required', 'ftp_hostname')->message('{field} is required');
        $validate->rule('required', 'ftp_username')->message('{field} is required');
        $validate->rule('required', 'ftp_password')->message('{field} is required');
        $validate->rule('required', 'ftp_port')->message('{field} is required');
        $validate->rule('numeric', 'ftp_port')->message('{field} must be a number');
        $validate->rule('required', 'ftp_timeout')->message('{field} is required');
        $validate->rule('numeric', 'ftp_timeout')->message('{field} must be a number');
        
        $validate->rule('required', 'ftp_store_location')->message('{field} is required');
        
        if (! empty($settings['ftp_hostname']) && ! empty($settings['ftp_username']) && ! empty($settings['ftp_password']) && ! empty($settings['ftp_port'])) {
            if ($settings['storage_location_status'] != '1') {
                return $validate; // don't validate connection if disabling
            }
            
            $validate->rule('ftp_connect', 'ftp_hostname', $settings)->message('Can\'t connect to entered {field}');
            if (! empty($settings['ftp_store_location'])) {
                $settings['ftp_store_location'] = $settings['ftp_store_location'];
                $validate->rule('ftp_writable', 'ftp_store_location', $settings)->message('{field} has to be writable by the FTP user');
            }
        }
        
        return $validate;
    }

    /**
     * Returns an instance of the Fileysystem object
     * 
     * @return \mithra62\BackupPro\Remote
     */
    public function getFileSystem()
    {
        $filesystem = new Remote(new m62Ftp([
            'host' => $this->settings['ftp_hostname'],
            'username' => $this->settings['ftp_username'],
            'password' => $this->settings['ftp_password'],
            'port' => $this->settings['ftp_port'],
            'passive' => (isset($this->settings['ftp_passive']) ? $this->settings['ftp_passive'] : '0'),
            'ssl' => (! empty($this->settings['ftp_ssl']) ? $this->settings['ftp_ssl'] : '0'),
            'timeout' => (! empty($this->settings['ftp_timeout']) ? $this->settings['ftp_timeout'] : '30')
        ]));
        
        $filesystem->getAdapter()->setRoot($this->settings['ftp_store_location']);
        $filesystem->checkBackupDirs();
        
        return $filesystem;
    }
}