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
 
namespace mithra62\BackupPro\Backup\Database;

/**
 * Backup Pro - Database Backup Interface
 *
 * All BackupPro database backup engines must implement this interface
 *
 * @package 	Backup\Database
 * @author		Eric Lamb <eric@mithra62.com>
 */
interface DbInterface
{
    /**
     * Performs the backup of a single table
     * @param string $table The name of the table to backup
     * @param bool $data Whether to include the data with the export
     */
    public function backupTable($table, $data = true);
    
    /**
     * Perfroms the backup of a stored procedure
     * @param string $procedure The name of the stored procedure to backup
     */
    public function backupProcedure($procedure);
    
    /**
     * Sets the parent interface for use in processing
     * @param \mithra62\BackupPro\Backup\Database $context
     */
    public function setContext(\mithra62\BackupPro\Backup\Database $context);
    
    /**
     * Initializes the backup
     * @return \mithra62\BackupPro\Backup\Database\DbAbstract
     */
    public function start();
    
    /**
     * Should return an array with the details for the Driver
     * @return array
     */
    public function getEngineDetails();
    
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