<?php
namespace mithra62\BackupPro\tests\Browser\EE3;

use mithra62\BackupPro\tests\Browser\SettingsTestAbstract;
use mithra62\BackupPro\tests\Browser\EE3Trait;
 
class SettingsTest extends SettingsTestAbstract 
{
    use EE3Trait;
    
    public $urls = array(
        'settings_general' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/general',
        'settings_db' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/db',
        'settings_files' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/files',
        'settings_cron' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/cron',
        'settings_ia' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/integrity_agent',
        'settings_license' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/license',
    );
    
    public function setUp()
    {
        
    }
    
    public function teardown()
    {
        
    }
}