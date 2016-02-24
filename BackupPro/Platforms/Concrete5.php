<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		2.0
 * @filesource 	./mithra62/BackupPro/Platforms/Eecms.php
 */
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Concrete5 as m62C5;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Eecms Bridge
 *
 * Contains the Eecms specific
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Concrete5 extends m62C5 implements PlatformInterface
{

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getCronCommands()
     */
    public function getBackupCronCommands(array $settings)
    {
        $url = '/backup_pro/cron';
        return array(
			 'file_backup' => array('url' => $url.'?type=file&backup_pro='.$settings['cron_query_key'], 'cmd' => 'curl "'.$url.'?type=file"'),
			 'db_backup' => array('url' => $url.'?type=db&backup_pro='.$settings['cron_query_key'], 'cmd' => 'curl "'.$url.'?type=db"')
		);
    }

    /**
     * (non-PHPdoc)
     * 
     * @ignore
     *
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getEmailDetails()
     */
    public function getIaCronCommands(array $settings)
    {
        $url = '/backup_pro/cron/integrity?backup_pro='.$settings['cron_query_key'];
		return array(
			'verify_backup_stability' => array('url' => $url, 'cmd' => '0 * * * * * curl "'.$url.'"')
		);
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getRestApiRouteEntry()
     */
    public function getRestApiRouteEntry(array $settings)
    {
        
    }
}