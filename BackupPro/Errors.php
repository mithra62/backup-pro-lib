<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/BackupPro.php
 */
namespace mithra62\BackupPro;

use mithra62\Errors as m62Errors;

/**
 * Backup Pro - Error Object
 *
 * Checks the base system to ensure everything's in place for use
 *
 * @package BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Errors extends m62Errors
{

    /**
     * The expected structure for the $settings array
     *
     * @var array
     */
    protected $settings_prototypes = array(
        'backup_state' => array(
            'storage_details' => '',
            'working_directory' => '',
            'db_backup_alert_threshold' => '',
            'file_backup_alert_threshold' => ''
        )
    );
    
    /**
     * Checks to ensure the storage location data is all proper
     * 
     * @param array $storage_locations            
     * @return \mithra62\BackupPro\Errors
     */
    public function checkStorageLocations(array $storage_locations)
    {
        if (! is_array($storage_locations) || count($storage_locations) == 0) {
            $this->setError('no_storage_locations_setup', 'no_storage_locations_setup');
        }
        
        return $this;
    }

    /**
     * Verifies the file backup locations exist
     * 
     * @param array $locations            
     * @return \mithra62\BackupPro\Errors
     */
    public function checkFileBackupLocations(array $locations)
    {
        if (count($locations) == 0) {
            $this->setError('no_backup_file_location', 'no_backup_file_location');
        }
        
        return $this;
    }

    /**
     * Verifies the working directory is usable
     * 
     * @param string $path            
     * @return \mithra62\BackupPro\Errors
     */
    public function checkWorkingDirectory($path)
    {
        if (! is_dir($path) || ! is_writable($path)) {
            $this->setError('invalid_working_directory', 'invalid_working_directory');
        }
        
        return $this;
    }

    /**
     * Runs the tests to make sure the backup directories exist and are writable
     * 
     * @param \mithra62\BackupPro\Backup\Storage $storage            
     * @return \mithra62\BackupPro\Errors
     */
    public function checkBackupDirs(\mithra62\BackupPro\Backup\Storage $storage)
    {
        $errors = array();
        $index = dirname(__FILE__) . '/../index.html';
        
        if (! is_writable($storage->getBackupDir())) {
            $errors[] = 'db_dir_missing';
            $errors[] = 'files_dir_missing';
        } else {
            if (! file_exists($storage->getDbBackupDir())) {
                if (! mkdir($storage->getDbBackupDir())) {
                    $errors[] = 'db_dir_missing';
                } else {
                    @copy($index, $storage->getDbBackupDir() . '/index.html');
                }
            } elseif (! is_writable($storage->getDbBackupDir())) {
                $errors[] = 'db_dir_not_writable';
            }
            
            if (! file_exists($storage->getFileBackupDir())) {
                if (! mkdir($storage->getFileBackupDir())) {
                    $errors[] = 'files_dir_missing';
                } else {
                    @copy($index, $storage->getFileBackupDir() . '/index.html');
                }
            } elseif (! is_writable($storage->getFileBackupDir())) {
                $errors[] = 'files_dir_not_writable';
            }
            
            if (! file_exists($storage->getDbBackupDir() . '/.meta')) {
                if (! mkdir($storage->getDbBackupDir() . '/.meta')) {
                    $errors[] = 'db_dir_meta_missing';
                }
            }
            
            if (! file_exists($storage->getFileBackupDir() . '/.meta')) {
                if (! mkdir($storage->getFileBackupDir() . '/.meta')) {
                    $errors[] = 'files_dir_meta_missing';
                }
            }
        }
        
        return $this;
    }

    /**
     * Wraps up the backup state errors
     * 
     * @param \mithra62\BackupPro\Backups $backups            
     * @param array $settings            
     * @return \mithra62\BackupPro\Errors
     */
    public function checkBackupState(\mithra62\BackupPro\Backups $backups, array $settings)
    {
        $keys = array_keys($this->settings_prototypes['backup_state']);
        foreach($keys AS $key) {
            if(!array_key_exists($key, $settings)) {
                throw new \InvalidArgumentException('$settings has to be in the format '.print_r($this->settings_prototypes['backup_state'], true));
            }
        }
        
        $backup_data = $backups->setBackupPath($settings['working_directory'])->getAllBackups($settings['storage_details']);
        $backup_meta = $backups->getBackupMeta($backup_data);
        $integrity = $backups->getIntegrity();
        
        if ($settings['db_backup_alert_threshold'] >= 1) {
            if ($backup_meta['database']['total_backups'] == 0) {
                $this->setError('no_db_backups_exist_yet', 'no_db_backups_exist_yet');
            } else 
                if (! $integrity->monitorDbBackupState($backup_meta['database'], $settings['db_backup_alert_threshold'])) {
                    $this->setError('db_backup_past_expectation', 'db_backup_past_expectation_stub');
                }
        }
        
        if ($settings['file_backup_alert_threshold'] >= 1 && count($settings['backup_file_location']) >= 1) {
            if ($backup_meta['files']['total_backups'] == 0) {
                $this->setError('no_file_backups_exist_yet', 'no_file_backups_exist_yet');
            } else 
                if (! $integrity->monitorFileBackupState($backup_meta['files'], $settings['file_backup_alert_threshold'])) {
                    $this->setError('file_backup_past_expectation', 'file_backup_past_expectation_stub');
                }
        }
        
        return $this;
    }
}