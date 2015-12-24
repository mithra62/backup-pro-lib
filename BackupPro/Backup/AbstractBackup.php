<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/AbstractBackup.php
 */
namespace mithra62\BackupPro\Backup;

use \mithra62\Traits\Log;
use \mithra62\Traits\Encoding;
use \mithra62\Traits\DateTime;

/**
 * Backup Pro - Abstract Backup Object
 *
 * Base class all Backup mechanisms must implement
 *
 * @package Backup
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class AbstractBackup implements BackupInterface
{
    use Log, Encoding, DateTime;

    /**
     * The progress object
     * 
     * @var Backup\Progress
     */
    protected $progress = null;

    /**
     * The Backup\Storage object
     * 
     * @var Backup\Storage
     */
    protected $storage = null;

    /**
     * The Backup object
     * 
     * @var \mithra62\BackupPro\Backup
     */
    protected $backup = null;

    /**
     * The regex object
     * 
     * @var \mithra62\Regex
     */
    protected $regex = null;

    /**
     * Sets the Backup object
     * 
     * @param \mithra62\BackupPro\Backup $backup            
     */
    public function setBackup(\mithra62\BackupPro\Backup $backup)
    {
        $this->backup = $backup;
        return $this;
    }

    /**
     * Returns an instance of the Backup object
     * 
     * @return \mithra62\BackupPro\Backup
     */
    public function getBackup()
    {
        return $this->backup;
    }

    /**
     * Sets the Regex object used for, among other things, file backup exclude filtering
     * 
     * @param \mithra62\Regex $regex            
     * @return \mithra62\BackupPro\Backup\AbstractBackup
     */
    public function setRegex(\mithra62\Regex $regex)
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
}