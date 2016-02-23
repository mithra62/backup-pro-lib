<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Prestashop.php
 */
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Prestashop as m62Prestashop;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Prestashop Bridge
 *
 * Contains the Prestashop specific items Backup Pro needs
 *
 * @package mithra62\BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Prestashop extends m62Prestashop implements PlatformInterface
{

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getCronCommands()
     */
    public function getBackupCronCommands(array $settings)
    {
        $url = $this->presta_context->link->getModuleLink('backup_pro', 'cron');
        return array(
            'file_backup' => array(
                'url' => $url . '?backup_pro=' . $settings['cron_query_key'] . '&backup=files&type=file',
                'cmd' => 'curl "' . $url . '?backup_pro=' . $settings['cron_query_key'] . '&backup=files&type=file"'
            ),
            'db_backup' => array(
                'url' => $url . '?backup_pro=' . $settings['cron_query_key'] . '&backup=files&type=db',
                'cmd' => 'curl "' . $url . '?backup_pro=' . $settings['cron_query_key'] . '&backup=files&type=db"'
            )
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getIaCronCommands()
     */
    public function getIaCronCommands(array $settings)
    {
        $url = $this->presta_context->link->getModuleLink('backup_pro', 'cron');
        return array(
            'verify_backup_stability' => array(
                'url' => $url . '?backup_pro=' . $settings['cron_query_key'] . '&integrity=check',
                'cmd' => '0 * * * * * curl "' . $url . '?backup_pro=' . $settings['cron_query_key'] . '&integrity=check"'
            )
        );
    }

    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getRestApiRouteEntry()
     */
    public function getRestApiRouteEntry(array $settings)
    {
        return $this->presta_context->link->getModuleLink('backup_pro', 'api').'?bp_method=';
    }
}