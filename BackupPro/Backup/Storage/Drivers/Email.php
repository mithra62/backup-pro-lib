<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Storage/Email.php
 */
namespace mithra62\BackupPro\Backup\Storage\Drivers;

use mithra62\BackupPro\Backup\Storage\AbstractStorage;
use mithra62\BackupPro\Exceptions\Backup\StorageException;

/**
 * Backup Pro - Email Storage Object
 *
 * Driver for storing files in an email box
 *
 * @package 	Backup\Storage\Driver
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Email extends AbstractStorage
{
    /**
     * The name of the Storage Driver
     * @var string
     */
    protected $name = 'backup_pro_email_storage_driver_name';
    
    /**
     * A description of the Storage Driver
     * @var string
     */
    protected $desc = 'backup_pro_email_storage_driver_description';
    
    /**
     * The settings the Storage Driver provides
     * @var string
     */
    protected $settings = array(
        'email_storage_emails' => '',
        'email_storage_attach_threshold' => '0',
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
    protected $icon_name = 'email';
    
    /**
     * A slug for use; must be unique
     * @var string
     */
    protected $short_name = 'email';

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
     * NOT USED!!
     * @param string $file The path to the file
     * @param string $type the backup type (file or database) we're processing
     * @return bool
     */
    public function removeFile($file, $type = 'database')
    {
        return true;
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
        if( $this->settings['email_storage_attach_threshold'] != '0' && $this->settings['email_storage_attach_threshold'] < filesize($file))
        {
            return false;    
        }
        
        $services = $this->getServices();
        $email = $services['email'];
        $platform = $services['platform'];
        $emails = explode(PHP_EOL, $this->settings['email_storage_emails']);
        if( $emails )
        {
            foreach($emails AS $to)
            {
                $email = $email->addTo($to);
            }
            
            $email = $email->setConfig($platform->getEmailConfig())->setSubject('email_backup_subject')->setMessage('na')->addAttachment($file)->send();
        }
        
        return true;
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
        $validate->rule('required', 'email_storage_attach_threshold')->message('{field} is required');
        $validate->rule('numeric', 'email_storage_attach_threshold')->message('{field} must be a number');
        
        $validate->rule('required', 'email_storage_emails')->message('{field} is required');
        if( !empty($settings['email_storage_emails']) )
        {
            $emails = explode("\n", trim($settings['email_storage_emails']));
            if($emails != '')
            {
                if( !is_array($emails) )
                {
                    $emails = explode("\n", $emails);
                }
            
                foreach($emails AS $email)
                {
                    if( !filter_var(trim($email), FILTER_VALIDATE_EMAIL) )
                    {
                        $validate->rule('false', 'email_storage_emails')->message('"'.trim($email).'" isn\'t a valid email');
                        //break;
                    }
                }
            }            
        }
        
        return $validate;
    }
}