<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/S3.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Backup\Storage\AbstractStorage;
use mithra62\BackupPro\Exceptions\Backup\StorageException;
use mithra62\BackupPro\Remote;
use mithra62\Remote\S3 as m62S3;

/**
 * Backup Pro - S3 Storage Object
 *
 * Driver for storing files in an email box
 *
 * @package Backup\Storage\Driver
 * @author Eric Lamb <eric@mithra62.com>
 */
class S3 extends AbstractStorage
{

    /**
     * The name of the Storage Driver
     * 
     * @var string
     */
    protected $name = 'backup_pro_s3_storage_driver_name';

    /**
     * A description of the Storage Driver
     * 
     * @var string
     */
    protected $desc = 'backup_pro_s3_storage_driver_description';

    /**
     * The settings the Storage Driver provides
     * 
     * @var string
     */
    protected $settings = array(
        's3_access_key' => '',
        's3_secret_key' => '',
        's3_bucket' => '',
        's3_region' => '',
        's3_optional_prefix' => '',
        's3_reduced_redundancy' => 0
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
    protected $icon_name = 's3';

    /**
     * A slug for use; must be unique
     * 
     * @var string
     */
    protected $short_name = 's3';

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
     * Removes a file from the remove service
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
        $validate->rule('required', 's3_access_key')->message('{field} is required');
        $validate->rule('required', 's3_secret_key')->message('{field} is required');
        $validate->rule('required', 's3_bucket')->message('{field} is required');
        
        $validate->rule('s3_connect', 's3_access_key', $settings)->message('Can\'t connect to {field}');
        if (! empty($settings['s3_bucket'])) {
            $validate->rule('s3_bucket_exists', 's3_bucket', $settings)->message('Your bucket doesn\'t appear to exist...');
            $validate->rule('s3_bucket_readable', 's3_bucket', $settings)->message('Your bucket doesn\'t appear to be readable...');
            $validate->rule('s3_bucket_writable', 's3_bucket', $settings)->message('Your bucket doesn\'t appear to be writable...');
        }
        
        return $validate;
    }

    /**
     * Returns the Filesystem object setup for work
     * 
     * @return \mithra62\BackupPro\Remote
     */
    public function getFileSystem()
    {
        $region = (isset($this->settings['s3_region']) ? $this->settings['s3_region'] : '');
        $client = m62S3::getRemoteClient($this->settings['s3_access_key'], $this->settings['s3_secret_key'], $region);
        $options = array();
        if ($this->settings['s3_reduced_redundancy'] == '1') {
            $options['StorageClass'] = 'REDUCED_REDUNDANCY';
        }
        
        $adapter = new m62S3($client, $this->settings['s3_bucket'], $this->settings['s3_optional_prefix'], $options);
        
        $filesystem = new Remote($adapter);
        $filesystem->checkBackupDirs();
        return $filesystem;
    }
}