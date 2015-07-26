<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/GoogleCloudStorage.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Remote;
use mithra62\Remote\Gcs AS m62Gcs; 

/**
 * Backup Pro - Google Cloud Storage Storage Object
 *
 * Driver for storing files in an email box
 *
 * @package 	Backup\Storage\Driver
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Gcs extends S3
{
    /**
     * The name of the Storage Driver
     * @var string
     */
    protected $name = 'backup_pro_gcs_storage_driver_name';
    
    /**
     * A description of the Storage Driver
     * @var string
     */
    protected $desc = 'backup_pro_gcs_storage_driver_description';
    
    /**
     * The settings the Storage Driver provides
     * @var string
     */
    protected $settings = array(
        'gcs_access_key' => '',
        'gcs_secret_key' => '',
        'gcs_bucket' => '',
        'gcs_optional_prefix' => '',
        'gcs_reduced_redundancy' => 0,
    );
    
    /**
     * The file name, sans .png extention, for the icon
     * @var string
     */
    protected $icon_name = 'gcs';
    
    /**
     * A slug for use; must be unique
     * @var string
     */
    protected $short_name = 'gcs';

    /**
     * Should validate the Driver settings using the \mithra62\Validate object
     * @param \mithra62\Validate $validate
     * @param array $settings
     * @param array $drivers
     * @return \mithra62\Validate
     */
    public function validateSettings(\mithra62\Validate $validate, array $settings, array $drivers = array())
    {
        $validate->rule('required', 'gcs_access_key')->message('{field} is required');
        $validate->rule('required', 'gcs_secret_key')->message('{field} is required');
        $validate->rule('required', 'gcs_bucket')->message('{field} is required');
        
        $validate->rule('gcs_connect', 'gcs_access_key', $settings)->message('Can\'t connect to {field}');
        if( !empty($settings['gcs_bucket']) )
        {
            $validate->rule('gcs_bucket_exists', 'gcs_bucket', $settings)->message('Your bucket doesn\'t appear to exist...');
            $validate->rule('gcs_bucket_readable', 'gcs_bucket', $settings)->message('Your bucket doesn\'t appear to be readable...');
            $validate->rule('gcs_bucket_writable', 'gcs_bucket', $settings)->message('Your bucket doesn\'t appear to be writable...');
        }  

        return $validate;
    }
    
    /**
     * Returns the Filesystem object setup for work
     * @return \mithra62\BackupPro\Remote
     */
    public function getFileSystem()
    {
        $client = m62Gcs::getRemoteClient($this->settings['gcs_access_key'], $this->settings['gcs_secret_key']);
        $options = array();
        if( $this->settings['gcs_reduced_redundancy'] == '1' )
        {
            $options['StorageClass'] = 'REDUCED_REDUNDANCY';
        }
        
        $adapter = new m62Gcs($client, $this->settings['gcs_bucket'], $this->settings['gcs_optional_prefix'], $options);

        $filesystem = new Remote( $adapter );
        $filesystem->checkBackupDirs();  
        return $filesystem;
    }
}