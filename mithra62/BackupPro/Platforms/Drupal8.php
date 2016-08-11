<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Platforms/Drupal8.php
 */
namespace mithra62\BackupPro\Platforms;

use JaegerApp\Platforms\AbstractPlatform;
use mithra62\BackupPro\Platforms\PlatformInterface;

/**
 * Backup Pro - Drupal 8 Bridge
 *
 * Contains the Drupal 8 specific helpers for needed info
 *
 * @package BackupPro\Platforms
 * @author Eric Lamb <eric@mithra62.com>
 */
class Drupal8 extends AbstractPlatform implements PlatformInterface
{
    /**
     * The settings table name
     * @var string
     */
    protected $settings_table = 'backup_pro_settings';
    
    protected $context = null;
    
    public function getBackupCronCommands(array $settings)
    {
        
    }
    
    public function getIaCronCommands(array $settings)
    {
        
    }
    
    public function getRestApiRouteEntry(array $settings)
    {
        
    }
    
    /**
     * Start Jaeger Object
     */
    
    public function getDbCredentials()
    {
        //$config = \Drupal::config('system.mail');
        $db = \Drupal::database();
        $connection = $db->getConnectionOptions();
        return array(
            'user' => $connection['username'],
            'password' => $connection['password'],
            'database' => $connection['database'],
            'host' => $connection['host'],
            'prefix' => $connection['prefix']['default'],
            'settings_table_name' => $connection['prefix']['default'] . $this->getSettingsTable()
        );        
    }
    
    public function getEmailConfig()
    {
        
    }
    
    public function getCurrentUrl()
    {
        
    }
    
    public function getSiteName()
    {
        
    }
    
    public function getTimezone()
    {
        
    }
    
    public function getSiteUrl()
    {
        
    }
    
    public function getEncryptionKey()
    {
        
    }
    
    public function getConfigOverrides()
    {
        return array();
    }
    
    public function redirect($url)
    {
        
    }
}