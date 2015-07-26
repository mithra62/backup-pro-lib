<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		2.0
 * @filesource 	./mithra62/BackupPro/Platforms/PlatformInterface.php
 */
 
namespace mithra62\BackupPro\Platforms;

/**
 * Backup Pro - Platform Interface
 *
 * Ensures the platform specific methods exist
 *
 * @package 	mithra62\BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
interface PlatformInterface
{
    /**
     * Returns the Backup Cron commands
     */
    public function getBackupCronCommands();
    
    /**
     * Returns the Integrity Agent Cron command
     */
    public function getIaCronCommands();
}