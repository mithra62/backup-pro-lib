<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/IntegrityAgent.php
 */
namespace mithra62\BackupPro\Backup;

use mithra62\Traits\DateTime;

/**
 * Backup Pro - Integrity Agent Object
 *
 * Performs checks against backups to ensure they're stable and able to restore
 *
 * @package Backup
 * @author Eric Lamb <eric@mithra62.com>
 */
class Integrity
{
    use DateTime;

    /**
     * The Storage object
     * 
     * @var \mithra62\BackupPro\Backup\Storage
     */
    protected $storage = null;

    /**
     * The Compress object
     * 
     * @var \mithra62\Compress
     */
    protected $compress = null;

    /**
     * The Backup Restore object
     * 
     * @var \mithra62\BackupPro\Restore
     */
    protected $restore = null;

    /**
     * The Shell object
     * 
     * @var \mithra62\Shell
     */
    protected $shell = null;

    /**
     * The database to test our database backups with
     * 
     * @var string
     */
    protected $test_db_name = false;

    /**
     * The details about the current backup system
     * 
     * @var array
     */
    protected $backup_info = array();

    /**
     * The Backpu Pro settings array
     * 
     * @var array
     */
    protected $settings = array();

    /**
     * The Files object
     * 
     * @var \mithra62\Files
     */
    protected $file = null;

    /**
     * The database connection info
     * 
     * @var array
     */
    private $db_conf = array();

    /**
     *
     * @ignore
     *
     */
    public function __construct()
    {}

    /**
     * Sets the database connection info for verifying database backups
     * 
     * @param array $db_info            
     */
    public function setDbConf(array $db_conf)
    {
        $this->db_conf = $db_conf;
        return $this;
    }

    /**
     * Returns the database connection details for verifying the database.
     * Also runs checks to ensure we're NOT using the main database
     * 
     * @return array
     */
    public function getDbConf()
    {
        return $this->db_conf;
    }

    /**
     * Sets the database connection info for verifying database backups
     * 
     * @param array $db_info            
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Returns the database connection details for verifying the database.
     * Also runs checks to ensure we're NOT using the main database
     * 
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Sets the database connection info for verifying database backups
     * 
     * @param array $db_info            
     */
    public function setBackupInfo(array $backup_info)
    {
        $this->backup_info = $backup_info;
        return $this;
    }

    /**
     * Returns the database connection details for verifying the database.
     * Also runs checks to ensure we're NOT using the main database
     * 
     * @return array
     */
    public function getBackupInfo()
    {
        return $this->backup_info;
    }

    public function setTestDbName($name)
    {
        $this->test_db_name = $name;
        return $this;
    }

    public function getTestDbName()
    {
        return $this->test_db_name;
    }

    /**
     * Sets the Files instances
     * 
     * @param \mithra62\Files $file            
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function setFile(\mithra62\Files $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Returns an instance of the Files object
     * 
     * @return \mithra62\Files
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns an instance of the Compress object
     * 
     * @return \mithra62\Compress
     */
    public function getCompress()
    {
        if (is_null($this->compress)) {
            $this->compress = new \mithra62\Compress();
        }
        
        return $this->compress;
    }

    /**
     * Sets an instance of the Storage object
     * 
     * @param Storage $storage            
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * Returns an instance of the Storage object
     * 
     * @return \mithra62\BackupPro\Backup\Storage
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Sets an instance of the Storage object
     * 
     * @param Storage $storage            
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function setShell(\mithra62\Shell $shell)
    {
        $this->shell = $shell;
        return $this;
    }

    /**
     * Returns an instance of the Shell object
     * 
     * @return \mithra62\Shell
     */
    public function getShell()
    {
        return $this->shell;
    }

    /**
     * Sets an instance of the Storage object
     * 
     * @param Storage $storage            
     * @return \mithra62\BackupPro\Backup\Integrity
     */
    public function setRestore(\mithra62\BackupPro\Restore $storage)
    {
        $this->restore = $storage;
        return $this;
    }

    /**
     * Returns an instance of the Restore object
     * 
     * @return \mithra62\BackupPro\Restore
     */
    public function getRestore()
    {
        return $this->restore;
    }

    /**
     * Sets an instance of the calling object
     * 
     * @param object $context            
     * @return \mithra62\BackupPro\Backups
     */
    public function setContext(\mithra62\BackupPro\Backups $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Returns an instance of the Storage object
     * 
     * @return \mithra62\BackupPro\Backups
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Checks the integrity of the backup in $path
     * 
     * @param array $backup_info            
     * @param string $type            
     * @return bool
     */
    public function checkBackup(array $backup_info, $type = 'database')
    {
        if (! is_array($backup_info['storage_locations'])) {
            return false;
        }
        
        foreach ($backup_info['storage_locations'] as $location) {
            if ($location['obj']->canDownload()) {
                $path = $location['obj']->getFilePath($backup_info['file_name'], $backup_info['backup_type']);
                if (file_exists($path) && is_readable($path)) {
                    switch ($type) {
                        case 'database':
                            return $this->checkDatabaseBackup($path);
                            break;
                        
                        case 'files':
                            return $this->checkFilesBackup($path);
                            break;
                    }
                }
            }
        }
    }

    /**
     * Checks the integrity of a database backup
     * 
     * @param string $path            
     */
    public function checkDatabaseBackup($path)
    {
        if (file_exists($path) && $this->getDbConf()) {
            $success = false;
            if ($this->getRestore()
                ->setDbInfo($this->getDbConf())
                ->setBackupInfo($this->getBackupInfo())
                ->database($this->getTestDbName(), $path, $this->getSettings(), $this->getShell())) {
                $this->clearTestDb();
                $success = true;
            }
            return $success;
        }
    }

    /**
     * Verifies a file backup can be unzipped
     * 
     * @param string $path            
     * @return boolean
     */
    public function checkFilesBackup($path)
    {
        if (file_exists($path)) {
            $storage = $this->getStorage();
            $working_dir = $storage->getBackupDir();
            $success = false;
            $compress = $this->getCompress();
            if (is_dir($working_dir) && is_writable($working_dir) && is_readable($working_dir)) {
                $folder = 'bp_' . md5(microtime(true));
                $temp = $working_dir . '/' . $folder;
                mkdir($temp);
                
                $backup_archive = $compress->extract($path, $temp);
                if ($backup_archive === true) {
                    $success = true;
                }
                
                $this->getFile()->deleteDir($temp, true);
            }
            return $success;
        }
    }

    /**
     * Checks the existing backups on the system and ensure's things are kosher
     * 
     * @param array $backup_meta            
     * @param array $settings            
     * @return multitype:boolean
     */
    public function monitorDbBackupState(array $backup_meta, $threshold)
    {
        if (! empty($backup_meta['newest_backup_taken_raw'])) {
            $db_check_hours = mktime(0, 0, 0, date('m'), date('d') - $threshold, date('Y'));
            if ($backup_meta['newest_backup_taken_raw'] > $db_check_hours) {
                return true;
            }
        }
    }

    /**
     * Checks the existing file backups on the system and ensure's things are kosher
     * 
     * @param array $backup_meta            
     * @param array $settings            
     * @return multitype:boolean
     */
    public function monitorFileBackupState(array $backup_meta, $threshold)
    {
        if (! empty($backup_meta['newest_backup_taken_raw'])) {
            $file_check_hours = mktime(0, 0, 0, date('m'), date('d') - $threshold, date('Y'));
            if ($backup_meta['newest_backup_taken_raw'] > $file_check_hours) {
                return true;
            }
        }
    }

    /**
     * Inspects the backup state and notifies when/if appropriate
     * 
     * @param array $backup_meta            
     * @param array $settings            
     * @param \mithra62\BackupPro\Notify $notify            
     * @return boolean
     */
    public function notifyBackupState(array $backup_meta, array $settings, array $errors, \mithra62\BackupPro\Notify $notify)
    {
        $last_notified = $settings['backup_missed_schedule_notify_email_last_sent'];
        $next_notified = mktime(date('G', $last_notified) + $settings['backup_missed_schedule_notify_email_interval'], date('i', $last_notified), 0, date('n', $last_notified), date('j', $last_notified), date('Y', $last_notified));
        
        if (time() > $next_notified) {
            $notify->setSettings($settings)->sendBackupState($settings['backup_missed_schedule_notify_emails'], $backup_meta, $errors);
        }
    }

    /**
     * Clears all the tables from the testing database
     * 
     * @return boolean
     */
    private function clearTestDb()
    {
        $tables = $this->getRestore()
            ->getDb()
            ->setDbName($this->getTestDbName())
            ->getTables();
        foreach ($tables as $table) {
            $this->getRestore()
                ->getDb()
                ->query(sprintf("DROP TABLE IF EXISTS `%s`; \n", $table));
        }
        
        $config = $this->getDbConf();
        $this->getRestore()
            ->getDb()
            ->setDbName($config['database']);
        return true;
    }
}