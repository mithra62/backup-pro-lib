<?php
namespace mithra62\BackupPro\tests\Browser\EE2;

use mithra62\BackupPro\tests\Browser\SettingsTestAbstract;
use mithra62\BackupPro\tests\Browser\EE2Trait;
 
class SettingsTest extends SettingsTestAbstract 
{
    use EE2Trait;
    
    public $urls = array(
        'settings_general' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=general',
        'settings_db' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=db',
        'settings_files' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=files',
        'settings_cron' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=cron',
        'settings_ia' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=integrity_agent',
        'settings_license' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=license',
    );
    
    public function setUp()
    {
        
    }
    
    public function teardown()
    {
        
    }
}