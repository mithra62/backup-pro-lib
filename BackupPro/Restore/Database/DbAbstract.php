<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database/DbInterface.php
 */
 
namespace mithra62\BackupPro\Restore\Database;

use mithra62\Traits\DateTime;
use mithra62\Traits\Log;

/**
 * Backup Pro - Database Backup Interface
 *
 * All BackupPro database backup engines must implement this interface
 *
 * @package 	Restore\Database
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class DbAbstract implements DbInterface
{
    use DateTime, Log; 
    
    /**
     * The name of the Database Backup Engine
     * @var string
     */
    protected $name = '';
    
    /**
     * A human readable shortname for the Database Backup Engine
     * @var string
     */
    protected $short_name = '';
    
    /**
     * The Database connection details
     * @var array
     */
    protected $db_info = array();
    
    /**
     * The backup types this restore engine can work with
     * @var array
     */
    protected $allowed_backup_types = array(
        '*'
    );    
    
    /**
     * Sets the context object
     * @param \mithra62\BackupPro\Restore\Database $context
     */
    public function setContext(\mithra62\BackupPro\Restore\Database $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Returns an instance of the calling object
     * @return \mithra62\BackupPro\Restore\Database
     */
    protected function getContext()
    {
        return $this->context;
    }    

    /**
     * Sets the command the engine will need to run on the CLI
     * @param string $cmd
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function setEngineCmd($cmd = null)
    {
        $this->engine_cmd = $cmd;
        return $this;
    }
    
    /**
     * Returns the engine CLI comman
     * @return string
     */
    public function getEngineCmd()
    {
        return $this->engine_cmd;
    }
    
    /**
     * Sets the shell object
     * @param \mithra62\Shell $shell
     * @return \mithra62\BackupPro\Backup\Database
     */
    public function setShell(\mithra62\Shell $shell)
    {
        $this->shell = $shell;
        return $this;
    }
    
    /**
     * Returns an instance of the Shell object
     * @return \mithra62\Shell
     */
    public function getShell()
    {
        return $this->shell;
    }
    
    /**
     * Returns the allowed backup types a Restore engine can work with
     * @return array
     */
    public function getAllowedBackupTypes()
    {
        return $this->allowed_backup_types;
    }
    
    /**
     * Checks whether a given backup engine can be restored using the selected restore engine
     * @param string $engine The backup engine shortname used to create the backup
     * @return boolean
     */
    public function canRestore($engine)
    {
        //wildcard to allow a restore engine to use all backup types
        if( in_array('*', $this->getAllowedBackupTypes()) )
        {
            return true;
        }
        
        return ( in_array($engine, $this->getAllowedBackupTypes()) );
    }

    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Storage\StorageInterface::getShortName()
     */
    public function getShortName()
    {
        return $this->short_name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Backup\Database\DbInterface::getDescription()
     */
    public function getDescription()
    {
        return $this->desc;
    }    
    
}