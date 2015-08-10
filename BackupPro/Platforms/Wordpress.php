<?php  
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Wordpress.php
 */
 
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Wordpress AS m62Wp;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Wordpress Bridge
 *
 * Contains the Wordpress specific items Backup Pro needs
 *
 * @package 	mithra62\BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Wordpress extends m62Wp implements PlatformInterface
{
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getCronCommands()
     */
    public function getBackupCronCommands()
    {
        return array();
		ee()->load->library('backup_pro_lib', null, 'backup_pro');
        return ee()->backup_pro->get_cron_commands();
    }
    
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getEmailDetails()
     */
    public function getIaCronCommands()
    {
        return array();
		ee()->load->library('backup_pro_lib', null, 'backup_pro');
        return ee()->backup_pro->get_ia_cron_commands();
    }
}