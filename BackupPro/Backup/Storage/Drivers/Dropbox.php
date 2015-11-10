<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Dropbox.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Backup\Storage\AbstractStorage;
use mithra62\BackupPro\Exceptions\Backup\StorageException;
use mithra62\BackupPro\Remote;
use mithra62\Remote\Dropbox AS m62Dropbox;

/**
 * Backup Pro - Dropbox Storage Object
 *
 * Driver for storing files on a remote Dropbox server
 *
 * @package 	Backup\Storage\Driver
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Dropbox extends AbstractStorage
{
    /**
     * The name of the Storage Driver
     * @var string
     */
    protected $name = 'backup_pro_dropbox_storage_driver_name';
    
    /**
     * A description of the Storage Driver
     * @var string
     */
    protected $desc = 'backup_pro_dropbox_storage_driver_description';
    
    /**
     * The settings the Storage Driver provides
     * @var string
     */
    protected $settings = array(
        'access_token' => '',
        'app_secret' => ''
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
    protected $icon_name = 'dropbox';
    
    /**
     * A slug for use; must be unique
     * @var string
     */
    protected $short_name = 'dropbox';

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
     * Removes a file from the remove service
     * @param string $file_name The path to the file
     * @param string $backup_type the backup type (file or database) we're processing
     * @return bool
     */
    public function removeFile($file_name, $backup_type = 'database')
    {
        $filesystem = $this->getFilesystem();
        $path = $backup_type.'/'.$file_name;
        if( $filesystem->has($path) )
        {
            return $filesystem->delete($path);
        }
    }
    
    /**
     * NOT USED!!
     * @param string $path The path to the file
     * @return bool
     */
    public function removeFiles($path)
    {   
        return true;
    }
    
    /**
     * NOT USED!!
     * @param string $path The path to the file
     */
    public function removeDir($path)
    {
        return true;
    }
    
    /**
     * NOT USED!!
     * @param string $file_name The full path to the backup
     * @param string $backup_type The type of backup, either database or files
     */
    public function getFilePath($file_name, $backup_type = 'database')
    {
        throw new StorageException('Unused');
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::clearFiles($path)
     */
    public function clearFiles()
    {
        return true;
    }
    
    /**
     * Should create a file using the Driver service
     * @param string $file The path to the file to create on the service
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
     * @param \mithra62\Validate $validate
     * @param array $settings
     * @param array $drivers
     * @return \mithra62\Validate
     */
    public function validateSettings(\mithra62\Validate $validate, array $settings, array $drivers = array())
    {
        if( $settings['sftp_username'] == '' && $settings['sftp_password'] == '' && $settings['sftp_private_key'] == '' )
        {
            $validate->rule('required', 'sftp_username')->message('Either a username and password OR a private key path must be supplied.');
            $validate->rule('required', 'sftp_password')->message('Either a username and password OR a private key path must be supplied.');
            $validate->rule('required', 'sftp_private_key')->message('Either a username and password OR a private key path must be supplied.');
        }
        else 
        {
            $validate->rule('sftp_connect', 'sftp_hostname', $settings)->message('Can\'t connect to entered {field}');
            if( !empty($settings['sftp_root']) )
            {
                $settings['sftp_root'] = $settings['sftp_root'];
                $validate->rule('sftp_writable', 'sftp_root', $settings)->message('{field} has to be writable by the SFTP user');
            }
        }
        
        if( $settings['sftp_private_key'] != '' )
        {
            $validate->rule('file', 'sftp_private_key')->message('{field} must be a file');
            $validate->rule('readable', 'sftp_private_key')->message('{field} must be readable by PHP');
        }
        
        $validate->rule('required', 'sftp_host')->message('{field} is required');
        $validate->rule('required', 'sftp_port')->message('{field} is required');
        $validate->rule('numeric', 'sftp_port')->message('{field} must be a number');
        $validate->rule('required', 'sftp_timeout')->message('{field} is required');
        $validate->rule('required', 'sftp_root')->message('{field} is required');
        $validate->rule('numeric', 'sftp_timeout')->message('{field} must be a number');
        
        return $validate;
    }
    
    /**
     * Returns an instance of the Fileysystem object
     * @return \mithra62\BackupPro\Remote
     */
    public function getFileSystem()
    {
        $client = m62Dropbox::getRemoteClient($this->settings['access_token'], $this->settings['app_secret']);
        $options = array();
        if( $this->settings['s3_reduced_redundancy'] == '1' )
        {
            $options['StorageClass'] = 'REDUCED_REDUNDANCY';
        }
        
        $adapter = new m62S3($client, $this->settings['s3_bucket'], $this->settings['s3_optional_prefix'], $options);

        $filesystem = new Remote( $adapter );
        $filesystem->checkBackupDirs();  
        return $filesystem;
    }
}