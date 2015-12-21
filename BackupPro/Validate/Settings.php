<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings.php
 */

namespace mithra62\BackupPro\Validate;

use mithra62\BackupPro\Validate;
use Aws\CloudFront\Exception\Exception;
use mysqli;

/**
 * Backup Pro - Settings Validation Object
 *
 * Contains all the Validation rules for the Settings details
 *
 * @package 	BackupPro\Validate
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Settings extends Validate
{   
    /**
     * The previously set settings 
     * @var array
     */
    protected $existing_settings = array();
    
    /**
     * Sets the previously set settings for the Validation object to use
     * @param array $settings
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setExistingSettings(array $settings)
    {
        $this->existing_settings = $settings;
        return $this;
    }
    
    /**
     * Returns the previously set settings 
     * @return array
     */
    public function getExistingSettings()
    {
        return $this->existing_settings;
    }
    
    /**
     * Validates the backup store location rules
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function workingDirectory($path)
    {
        //ensure we don't have a local storage location setup for this path already
        $existing_settings = $this->getExistingSettings();
        if( !empty($existing_settings['storage_details']) && is_array($existing_settings['storage_details']) )
        {
            foreach($existing_settings['storage_details'] AS $key => $value)
            {
                if( $value['storage_location_driver'] =='local' && $value['backup_store_location'] == $path )
                {
                    $this->rule('false', 'working_directory')->message('{field} can\'t be set as a Local Storage path');
                    break;
                }
            }
        }
        
        if( $existing_settings['working_directory'] != $path )
        {
            $this->rule('dir_empty', 'working_directory')->message('{field} has to be an empty directory');
        }
        
        $this->rule('required', 'working_directory')->message('{field} is required');
        $this->rule('writable', 'working_directory')->message('{field} has to be writable');
        $this->rule('dir', 'working_directory')->message('{field} has to be a directory');
        $this->rule('readable', 'working_directory')->message('{field} has to be readable');
        
        return $this;
    }
    
    /**
     * Validates the date format rules
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function dateFormat()
    {
        $this->rule('required', 'date_format')->message('{field} is required');
        return $this;
    }
    
    /**
     * Validates the Dashboard Recent Total setting value
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function dashboardRecentTotal()
    {
        $this->rule('required', 'dashboard_recent_total')->message('{field} is required');
        $this->rule('min', 'dashboard_recent_total', '1')->message('{field} must be a number greater than 1');
        return $this;
    }
    
    /**
     * Validates the Auto Theshold setting value
     * @param array $data The form data
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function autoThreshold(array $data)
    {
        $this->rule('required', 'auto_threshold')->message('{field} is required');
        if( !empty($data['auto_threshold']) && $data['auto_threshold'] == 'custom' )
        {
            $this->rule('required', 'auto_threshold_custom')->message('{field} is required');
            $this->rule('numeric', 'auto_threshold_custom')->message('{field} must be a number');
            
            if( $data['meta']['global']['total_space_used_raw'] > '100000000')
            {
                $this->rule('min', 'auto_threshold_custom', $data['meta']['global']['total_space_used_raw'])
                     ->message('You\'re already using '.$data['meta']['global']['total_space_used'].' so {field} must be at least "'.$data['meta']['global']['total_space_used_raw'].'"');
            }
            else
            {
                $this->rule('min', 'auto_threshold_custom', '100000000')->message('{field} must be at least 100MB (100000000)');
            }     
        }
        else 
        {
            if( $data['auto_threshold'] != '0')
            {
                $this->rule('numeric', 'auto_threshold')->message('{field} must be a number');
                $this->rule('min', 'auto_threshold', $data['meta']['global']['total_space_used_raw'])
                      ->message('You\'re already using '.$data['meta']['global']['total_space_used'].' so {field} must be at least that or a custom value over "'.$data['meta']['global']['total_space_used_raw'].'"');                
            }
        }
        return $this;
    }
    
    public function cronQueryKey($string)
    {
        $this->rule('required', 'cron_query_key')->message('{field} is required');
        $this->rule('alphanum', 'cron_query_key')->message('{field} must be alpha-numeric only');
        return $this;
    }
    
    /**
     * Validates the database backup method setting value
     * @param array $data The form data
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function dbBackupMethod(array $data)
    {
        $this->rule('required', 'db_backup_method')->message('{field} is required');
        if( !empty($data['db_backup_method']) && $data['db_backup_method'] == 'mysqldump' )
        {
            $this->rule('required', 'mysqldump_command')->message('{field} is required');
        }
        
        return $this;
    }
    
    /**
     * Validates the php backup method select chunk limit
     * @param number $limit
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function phpBackupMethodSelectChunkLimit($limit)
    {
        $this->rule('required', 'php_backup_method_select_chunk_limit')->message('SELECT Chunk Limit is required');
        $this->rule('numeric', 'php_backup_method_select_chunk_limit')->message('SELECT Chunk Limit must be a number');
        return $this;
    }
    
    /**
     * Validates the database restore method setting value
     * @param array $data
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function dbRestoreMethod(array $data)
    {
        $this->rule('required', 'db_restore_method')->message('{field} is required');
        if( !empty($data['db_restore_method']) && $data['db_restore_method'] == 'mysql' )
        {
            $this->rule('required', 'mysqlcli_command')->message('{field} is required');
        }
        
        return $this;
    }
    
    /**
     * Validates the File Backup Alert Frequency
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function fileBackupAlertThreshold()
    {
        $this->rule('required', 'file_backup_alert_threshold')->message('{field} is required');
        $this->rule('numeric', 'file_backup_alert_threshold')->message('{field} must be a number');
        return $this;
    }
    
    /**
     * Validates the database backup alert threshold value
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function dbBackupAlertThreshold()
    {
        $this->rule('required', 'db_backup_alert_threshold')->message('{field} is required');
        $this->rule('numeric', 'db_backup_alert_threshold')->message('{field} must be a number');
        return $this;
    }
    
    /**
     * Validates a license number
     * @param array $data
     */
    public function licenseNumber(array $data)
    {
        $this->rule('required', 'license_number')->message('{field} is required');
        $this->rule('license_key', 'license_number')->message('{field} isn\'t a valid license key');
    }
    
    /**
     * Validates the file backup location setting
     * @param string $paths
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupFileLocation($paths)
    {
        if($paths == '')
        {
            $this->rule('required', 'backup_file_location')->message('{field} is required');
        }
        else 
        {
            if( !is_array($paths) )
            {
                $paths = explode("\n", $paths);
            }
            
            foreach($paths AS $path)
            {
                $path = trim($path);
                if( !$this->regex->validate($path) )
                {
                    if( file_exists($path) )
                    {
                        if( !is_readable($path) )
                        {
                            $this->rule('false', 'backup_file_location')->message('"'.$path.'" isn\'t a readable path by PHP.');
                        }
                    }
                    else
                    {
                        $this->rule('false', 'backup_file_location')->message('"'.$path.'" isn\'t a valid regular expression or path on the system.');
                    }
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Validates the exclude paths setting
     * @param string $paths
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function excludePaths($paths)
    {
        if( !is_array($paths) )
        {
            $paths = explode("\n", $paths);
        }
        
        foreach($paths AS $path)
        {
            $path = trim($path);
            if( !$this->regex->validate($path) )
            {
                if( file_exists($path) )
                {
                    if( !is_readable($path) )
                    {
                        $this->rule('false', 'exclude_paths')->message('"'.$path.'" isn\'t a readable path by PHP.');
                    }
                }
                else
                {
                    $this->rule('false', 'exclude_paths')->message('"'.$path.'" isn\'t a valid regular expression or path on the system.');
                }
            }
        }
        
        return $this;        
    }
    
    /**
     * Validates the  Maximum Database Backups setting value
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function maxDbBackups()
    {
        $this->rule('required', 'max_db_backups')->message('{field} is required');
        $this->rule('numeric', 'max_db_backups')->message('{field} must be a number');
        return $this;
    }
    
    /**
     * Validates the Maximum File Backups setting value
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function maxFileBackups()
    {
        $this->rule('required', 'max_file_backups')->message('{field} is required');
        $this->rule('numeric', 'max_file_backups')->message('{field} must be a number');
        return $this;
    }
    
    /**
     * Validates the file backup location setting
     * @param string $emails
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function cronNotifyEmails($emails)
    {
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
                    $this->rule('false', 'cron_notify_emails')->message('"'.trim($email).'" isn\'t a valid email');
                    //break;
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Validates we have a subject for cron notify emails
     * @param string $subject
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function cronNotifyEmailSubject($subject)
    {
        if($subject == '')
        {
            $this->rule('required', 'cron_notify_email_subject')->message('{field} is required');
        }
        
        return $this;
    }
    
    /**
     * Validates we have a message for cron notify emails
     * @param string $message
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function cronNotifyEmailMessage($message)
    {
        if($message == '')
        {
            $this->rule('required', 'cron_notify_email_message')->message('{field} is required');
        }
        
        return $this;
    }
    
    /**
     * Validates the file backup location setting
     * @param string $emails
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmail($emails)
    {
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
                    $this->rule('false', 'backup_missed_schedule_notify_emails')->message('"'.trim($email).'" isn\'t a valid email');
                    //break;
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Validates the subject for the missed backup email
     * @param string $subject
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmailSubject($subject)
    {
        if($subject == '')
        {
            $this->rule('required', 'backup_missed_schedule_notify_email_subject')->message('{field} is required');
        }
        
        return $this;
    }
    
    /**
     * Validates the message for the missed backup email
     * @param string $message
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmailMessage($message)
    {
        if($message == '')
        {
            $this->rule('required', 'backup_missed_schedule_notify_email_message')->message('{field} is required');
        }
        
        return $this;
    }
    
    /**
     * Validates the total verifications per execution value
     * @param string $message
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function totalVerificationsPerExecution($total)
    {
        if($total == '')
        {
            $this->rule('required', 'total_verifications_per_execution')->message('{field} is required');
        }
        
        $this->rule('integer', 'total_verifications_per_execution')->message('{field} must be a whole number');
        
        return $this;
    }
    
    /**
     * Validates the total verifications per execution value for the missed backup email
     * @param number $total
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmailInterval($total)
    {
        if($total == '')
        {
            $this->rule('required', 'backup_missed_schedule_notify_email_interval')->message('{field} is required');
        }
        
        $this->rule('integer', 'backup_missed_schedule_notify_email_interval')->message('{field} must be a whole number');
        
        return $this;
    }
    
    /**
     * Validates the database name for the Integrity Agent
     * @param string $name The database name to validate
     * @param array $credentials The connection details 
     */
    public function dbVerificationDbName($name, array $credentials)
    {
        if( $name != '' )
        {
            if( $name == $credentials['database'] )
            {
                $this->rule('false', 'db_verification_db_name')->message('"'.$name.'" is the site db; you can\'t use that for verification');
            }
            else
            {
                try 
                {
                    $link = @new mysqli($credentials['host'], $credentials['user'], $credentials['password'], $name);
                    if( $link->connect_errno )
                    {
                        $this->rule('false', 'db_verification_db_name')->message('"'.$name.'" isn\'t available to your configured database connection');
                    }
                    else 
                    {
                        $tables = $link->query("SHOW TABLES");
                        if( $tables->num_rows != '0' )
                        {
                            $this->rule('false', 'db_verification_db_name')->message('"'.$name.'" isn\'t an empty database; remove all the tables and try again.');
                        }
                        
                        @$link->close();
                    }
                }
                catch(Exception $e)
                {
                    $this->rule('false', 'db_verification_db_name')->message('"'.$name.'" isn\'t available to your configured database connection');
                }
            }
        }
    }
   
    /**
     * Checks the entire settings array for issues
     * @param array $data
     * @return bool
     */
    public function check(array $data, array $extra = array())
    {
        if( isset($data['working_directory']) )
        {
            $this->workingDirectory($data['working_directory']);
        }
    
        if( isset($data['dashboard_recent_total']) )
        {
            $this->dashboardRecentTotal();
        }
    
        if( isset($data['auto_threshold']) )
        {
            $this->autoThreshold($data);
        }
    
        if( isset($data['date_format']) )
        {
            $this->dateFormat();
        }
    
        if( isset($data['ftp_hostname']) )
        {
            $this->ftpConnectionInfo($data);
        }
        
        if( isset($data['s3_access_key']) )
        {
            $this->s3ConnectionInfo($data);
        }
        
        if( isset($data['backup_file_location']) )
        {
            $this->backupFileLocation($data['backup_file_location']);
        }
        
        if( isset($data['exclude_paths']) )
        {
            $this->excludePaths($data['exclude_paths']);
        }
        
        if( isset($data['file_backup_alert_threshold']) )
        {
            $this->fileBackupAlertThreshold();
        }
        
        if( isset($data['max_file_backups']) )
        {
            $this->maxFileBackups();
        }
        
        if( isset($data['db_backup_alert_threshold']) )
        {
            $this->dbBackupAlertThreshold();
        }
        
        if( isset($data['max_db_backups']) )
        {
            $this->maxDbBackups();
        }
        
        if( isset($data['db_backup_method']) )
        {
            $this->dbBackupMethod($data);
        }
        
        if( isset($data['db_restore_method']) )
        {
            $this->dbRestoreMethod($data);
        }
           
        if( isset($data['license_number']) )
        {
            $this->licenseNumber($data);
        }
        
        if( isset($data['cron_notify_emails']) )
        {
            $this->cronNotifyEmails($data['cron_notify_emails']);
        }
        
        if( isset($data['cron_notify_email_subject']) )
        {
            $this->cronNotifyEmailSubject($data['cron_notify_email_subject']);
        }
        
        if( isset($data['cron_notify_email_message']) )
        {
            $this->cronNotifyEmailMessage($data['cron_notify_email_message']);
        }
        
        if( isset($data['backup_missed_schedule_notify_emails']) )
        {
            $this->backupMissedScheduleNotifyEmail($data['backup_missed_schedule_notify_emails']);
        }
        
        if( isset($data['backup_missed_schedule_notify_email_subject']) )
        {
            $this->backupMissedScheduleNotifyEmailSubject($data['backup_missed_schedule_notify_email_subject']);
        }
        
        if( isset($data['backup_missed_schedule_notify_email_message']) )
        {
            $this->backupMissedScheduleNotifyEmailMessage($data['backup_missed_schedule_notify_email_message']);
        }
        
        if( isset($data['total_verifications_per_execution']) )
        {
            $this->totalVerificationsPerExecution($data['total_verifications_per_execution']);
        }
        
        if( isset($data['backup_missed_schedule_notify_email_interval']) )
        {
            $this->backupMissedScheduleNotifyEmailInterval($data['backup_missed_schedule_notify_email_interval']);
        }
        
        if( isset($data['db_verification_db_name']) )
        {
            $this->dbVerificationDbName( $data['db_verification_db_name'], $extra['db_creds'] );
        }
        
        if( isset($data['cron_query_key']) )
        {
            $this->cronQueryKey( $data['cron_query_key'] );
        }
        
        if( isset($data['php_backup_method_select_chunk_limit']) )
        {
            $this->phpBackupMethodSelectChunkLimit( $data['php_backup_method_select_chunk_limit'] );
        }
    
        $this->val($data);
        if( !$this->hasErrors() )
        {
            return true;
        }
    }    
}