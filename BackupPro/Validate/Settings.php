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
     * Validates the backup store location rules
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function workingDirectory()
    {
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
        $this->rule('min', 'dashboard_recent_total', '1')->message('{field} must be 1 characters at least');
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
                $this->rule('min', 'auto_threshold', $data['meta']['global']['total_space_used_raw'])
                      ->message('You\'re already using '.$data['meta']['global']['total_space_used'].' so {field} must be at least that or a custom value over "'.$data['meta']['global']['total_space_used_raw'].'"');                
            }
        }
        return $this;
    }
    
    /**
     * Validates the database backup method setting value
     * @param array $data The form data
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function dbBackupMetho(array $data)
    {
        $this->rule('required', 'db_backup_method')->message('{field} is required');
        if( !empty($data['db_backup_method']) && $data['db_backup_method'] == 'mysqldump' )
        {
            $this->rule('required', 'mysqldump_command')->message('{field} is required');
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
        if( !is_array($paths) )
        {
            $paths = explode("\n", $paths);
        }
        
        foreach($paths AS $path)
        {
            $path = trim($path);
            if( !file_exists($path) )
            {
                $this->rule('false', 'backup_file_location')->message('"'.$path.'" doesn\'t appear to exist...');
                //break;
            }
            
            elseif( !is_readable($path) )
            {
                $this->rule('false', 'backup_file_location')->message('"'.$path.'" isn\'t readable by PHP.');
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
     * Checks the entire settings array for issues
     * @param array $data
     * @return bool
     */
    public function check(array $data)
    {
        if( isset($data['working_directory']) )
        {
            $this->workingDirectory();
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
            $this->dbBackupMetho($data);
        }
           
        if( isset($data['license_number']) )
        {
            $this->licenseNumber($data);
        }
    
        $this->val($data);
        if( !$this->hasErrors() )
        {
            return true;
        }
    }    
}