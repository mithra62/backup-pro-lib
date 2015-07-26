<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		2.0
 * @filesource 	./mithra62/BackupPro/Platforms/Wp.php
 */
 
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Wp AS m62Wp;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - WordPress Bridge
 *
 * Contains the WordPress specific 
 *
 * @package 	mithra62\BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Wp extends m62Wp implements PlatformInterface
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