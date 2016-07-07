<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Magento.php
 */
namespace mithra62\BackupPro\Platforms;

use JaegerApp\Platforms\Magento as m62Mag;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Magento Bridge
 *
 * Contains the Magento specific items Backup Pro needs
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Magento extends m62Mag implements PlatformInterface
{
    public function getBackupCronCommands(array $settings)
    {
        
    }
    
    public function getIaCronCommands(array $settings)
    {
        
    }
}