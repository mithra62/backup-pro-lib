<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Craft.php
 */
namespace mithra62\BackupPro\Platforms;

use mithra62\Platforms\Craft as m62Craft;
use mithra62\BackupPro\Platforms\PlatformInterface;
use Craft\UrlHelper;

/**
 * Backup Pro - Craft Bridge
 *
 * Contains the Craft specific helpers for needed info
 *
 * @package BackupPro\Platforms
 * @author Eric Lamb <eric@mithra62.com>
 */
class Craft extends m62Craft implements PlatformInterface
{

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getCronCommands()
     */
    public function getBackupCronCommands(array $settings)
    {
        $config = \Craft\craft()->config;
        $trigger = $config->get('actionTrigger');
        $url = UrlHelper::getSiteUrl() . $trigger . '/backupPro/cron/backup';
        return array(
            'file_backup' => array(
                'url' => $url . '?type=file&backup_pro=' . $settings['cron_query_key'],
                'cmd' => 'curl "' . $url . '?type=file&backup_pro=' . $settings['cron_query_key'] . '"',
                'type' => 'curl'
            ),
            'db_backup' => array(
                'url' => $url . '?type=db&backup_pro=' . $settings['cron_query_key'],
                'cmd' => 'curl "' . $url . '?type=db&backup_pro=' . $settings['cron_query_key'] . '"',
                'type' => 'curl'
            ),
            'console_file_backup' => array(
                'url' => '',
                'cmd' => 'php craft/app/etc/console/yiic.php backup file [--notify="yes"]',
                'type' => 'console'
            ),
            'console_db_backup' => array(
                'url' => '',
                'cmd' => 'php craft/app/etc/console/yiic.php backup database [--notify="yes"]',
                'type' => 'console'
            )
        );
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getIaCronCommands()
     */
    public function getIaCronCommands(array $settings)
    {
        $config = \Craft\craft()->config;
        $trigger = $config->get('actionTrigger');
        $url = UrlHelper::getSiteUrl() . $trigger . '/backupPro/cron/integrity?backup_pro=' . $settings['cron_query_key'];
        return array(
            'verify_backup_stability' => array(
                'url' => $url,
                'cmd' => 'curl "' . $url,
                'type' => 'curl'
            ),
            'console_verify_backup_stability' => array(
                'url' => '',
                'cmd' => 'php craft/app/etc/console/yiic.php backup integrity',
                'type' => 'console'
            )
        );
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\Platforms\Craft::getConfigOverrides()
     */
    public function getConfigOverrides()
    {
        $path = rtrim(\Craft\craft()->path->getConfigPath(), '/') . '/backuppro.php';
        if (file_exists($path)) {
            $config = include ($path);
            if (is_array($config)) {
                return $config;
            }
        }
        
        return array();
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Platforms\PlatformInterface::getRestApiRouteEntry()
     */
    public function getRestApiRouteEntry(array $settings)
    {
        
    }
}