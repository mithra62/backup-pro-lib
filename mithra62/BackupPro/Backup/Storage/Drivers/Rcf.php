<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Rcf.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Backup\Storage\AbstractStorage;
use mithra62\BackupPro\Exceptions\Backup\StorageException;
use mithra62\BackupPro\Remote;
use mithra62\Remote\Rcf as m62Rcf;

/**
 * Backup Pro - Rackspace Cloud Files Storage Object
 *
 * Driver for storing files on Rackspace Cloud Files
 *
 * @package Backup\Storage\Driver
 * @author Eric Lamb <eric@mithra62.com>
 */
class Rcf extends AbstractStorage
{

    /**
     * The name of the Storage Driver
     * 
     * @var string
     */
    protected $name = 'backup_pro_rcf_storage_driver_name';

    /**
     * A description of the Storage Driver
     * 
     * @var string
     */
    protected $desc = 'backup_pro_rcf_storage_driver_description';

    /**
     * The settings the Storage Driver provides
     * 
     * @var string
     */
    protected $settings = array(
        'rcf_username' => '',
        'rcf_api' => '',
        'rcf_container' => '',
        'rcf_location' => 'us',
        'rcf_optional_prefix' => ''
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
    protected $icon_name = 'rcf';

    /**
     * A slug for use; must be unique
     * 
     * @var string
     */
    protected $short_name = 'rcf';

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
     * NOT USED!!
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
        $validate->rule('required', 'rcf_username')->message('{field} is required');
        $validate->rule('required', 'rcf_api')->message('{field} is required');
        $validate->rule('required', 'rcf_container')->message('{field} is required');
        $validate->rule('required', 'rcf_location')->message('{field} is required');
        if (! empty($settings['rcf_username']) && ! empty($settings['rcf_api']) && ! empty($settings['rcf_container']) && ! empty($settings['rcf_location'])) {
            if ($settings['storage_location_status'] != '1') {
                return $validate; // don't validate connection if disabling
            }
            
            $validate->rule('rcf_connect', 'rcf_username', $settings)->message('Can\'t connect to this account');
            if (! empty($settings['rcf_container'])) {
                $validate->rule('rcf_container_exists', 'rcf_container', $settings)->message('Your container doesn\'t appear to exist...');
                $validate->rule('rcf_container_readable', 'rcf_container', $settings)->message('Your container doesn\'t appear to be readable...');
                $validate->rule('rcf_container_writable', 'rcf_container', $settings)->message('Your container doesn\'t appear to be writable...');
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
        $container = m62Rcf::getRemoteClient($this->settings);
        $filesystem = new Remote(new m62Rcf($container, $this->settings['rcf_optional_prefix']));
        $filesystem->checkBackupDirs();
        
        return $filesystem;
    }
}