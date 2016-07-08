<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/AbstractRestore.php
 */
namespace mithra62\BackupPro\Restore;

use \JaegerApp\Traits\Log;
use \JaegerApp\Traits\Encoding;
use \JaegerApp\Traits\DateTime;

/**
 * Backup Pro - Abstract Restore Object
 *
 * Base class all Restore mechanisms must implement
 *
 * @package Restore
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class AbstractRestore implements RestoreInterface
{
    use Log, Encoding, DateTime;

    /**
     * The progress object
     * 
     * @var Backup\Progress
     */
    protected $progress = null;

    /**
     * The backup info array for the backup we want to restore
     * 
     * @var array
     */
    protected $backup_info = array();

    /**
     * The Backup\Storage object
     * 
     * @var Backup\Storage
     */
    protected $storage = null;

    /**
     * The Backup object
     * 
     * @var \mithra62\BackupPro\Restore
     */
    protected $restore = null;

    /**
     * The regex object
     * 
     * @var \mithra62\Regex
     */
    protected $regex = null;

    /**
     * Sets the Backup object
     * 
     * @param \mithra62\BackupPro\Restore $restore            
     */
    public function setRestore(\mithra62\BackupPro\Restore $restore)
    {
        $this->restore = $restore;
        return $this;
    }

    /**
     * Returns an instance of the Backup object
     * 
     * @return \mithra62\BackupPro\Restore
     */
    public function getRestore()
    {
        return $this->restore;
    }

    /**
     * Sets the Regex object used for, among other things, file backup exclude filtering
     * 
     * @param \mithra62\Regex $regex            
     * @return \mithra62\BackupPro\Backup\AbstractBackup
     */
    public function setRegex(\JaegerApp\Regex $regex)
    {
        $this->regex = $regex;
        return $this;
    }

    /**
     * Returns an instance of the Regex object
     * 
     * @return \mithra62\Regex
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Sets the Backup Info for the backup we want to restore
     * 
     * @param array $backup_info            
     * @return \mithra62\BackupPro\Restore
     */
    public function setBackupInfo(array $backup_info)
    {
        $this->backup_info = $backup_info;
        return $this;
    }

    /**
     * Returns the Backup Info for the backup we want to restore
     * 
     * @return array
     */
    public function getBackupInfo()
    {
        return $this->backup_info;
    }
}