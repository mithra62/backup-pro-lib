<?php
namespace mithra62\BackupPro\tests\Browser\WP;

use mithra62\BackupPro\tests\Browser\SettingsTestAbstract;
use mithra62\BackupPro\tests\Browser\WpTrait;
 
class SettingsTest extends SettingsTestAbstract 
{
    use WpTrait;
    
    public $urls = array(
        'settings_general' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=general',
        'settings_db' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=db',
        'settings_files' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=files',
        'settings_cron' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=cron',
        'settings_ia' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=integrity_agent',
        'settings_license' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=license',
    );
    
    public function setUp()
    {
        
    }
    
    public function teardown()
    {
        
    }
}