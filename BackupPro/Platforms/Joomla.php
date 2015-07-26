<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		2.0
 * @filesource 	./mithra62/BackupPro/Platforms/Joomla.php
 */
 
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Joomla AS m62Joomla;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Joomla Bridge
 *
 * Contains the Joomla specific 
 *
 * @package 	mithra62\BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Joomla extends m62Joomla implements PlatformInterface
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