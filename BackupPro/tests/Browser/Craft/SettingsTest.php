<?php
namespace mithra62\BackupPro\tests\Browser\Craft;

use mithra62\BackupPro\tests\Browser\SettingsTestAbstract;
use mithra62\BackupPro\tests\Browser\CraftTrait;
 
class SettingsTest extends SettingsTestAbstract 
{
    use CraftTrait;
    
    public $urls = array(
        'settings_general' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings',
        'settings_db' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=db',
        'settings_files' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=files',
        'settings_cron' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=cron',
        'settings_ia' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=ia',
        'settings_license' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=license',
    );
    
    public function setUp()
    {
        
    }
    
    public function teardown()
    {
        
    }
}