<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Perch.php
 */
 
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Perch AS m62Perch;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Perch Bridge
 *
 * Contains the Perch specific 
 *
 * @package 	mithra62\BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Perch extends m62Perch implements PlatformInterface
{
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getCronCommands()
     */
    public function getCronCommands()
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
}