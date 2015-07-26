<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Restore/Database/DbInterface.php
 */
 
namespace mithra62\BackupPro\Restore\Database;

/**
 * Backup Pro - Database Restore Interface
 *
 * All BackupPro database restore engines must implement this interface
 *
 * @package 	Restore\Database
 * @author		Eric Lamb <eric@mithra62.com>
 */
interface DbInterface
{
    /**
     * Restores a database using the given logic
     * @param string $database The database we want to restore
     * @param string $restore_file The full path to the SQL file we want to use
     * @return boolean
     */    
    public function restore($database, $restore_file);

    /**
     * Should return the short name for the driver
     * @return string
     */
    public function getShortName();
    
    /**
     * Returns the name of the Driver
     * @return string
    */
    public function getName();
    
    /**
     * Should return the short name for the driver
     * @return string
    */
    public function getDescription();    
}