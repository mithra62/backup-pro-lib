<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/BackupInterface.php
 */
 
namespace mithra62\BackupPro\Backup;

/**
 * Backup Pro - Backup Interface
 *
 * All Backup methods should implement this interface
 *
 * @package 	Backup
 * @author		Eric Lamb <eric@mithra62.com>
 */
interface BackupInterface
{
    /**
     * Writes out the initial backup details file
     * @param \mithra62\BackupPro\Backup\Details $details
     * @param string $file_path The path to the backup 
     * @param string $backup_method What type of backup was taken
     */
    public function writeDetails(\mithra62\BackupPro\Backup\Details $details, $file_path, $backup_method = 'php');
}