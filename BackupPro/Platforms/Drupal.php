<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Drupal.php
 */
 
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Drupal AS m62Drupal;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Drupal Bridge
 *
 * Contains the Drupal specific 
 *
 * @package 	mithra62\BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Drupal extends m62Drupal implements PlatformInterface
{
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getBackupCronCommands()
     */
    public function getBackupCronCommands()
    {
        return array();
    }
    
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getEmailDetails()
     */
    public function getEmailDetails(array $details)
    {
        return array();
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getIaCronCommands()
     */
    public function getIaCronCommands()
    {
        return array();
    }    
}