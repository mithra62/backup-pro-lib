<?php
namespace mithra62\BackupPro\tests\Browser\Craft;

use mithra62\BackupPro\tests\Browser\DashboardTestAbstract;
use mithra62\BackupPro\tests\Browser\CraftTrait;

class DashboardTest extends DashboardTestAbstract
{
    use CraftTrait;
    
    public $urls = array(
        'dashboard' => 'http://eric.craft.clean.mithra62.com/admin/backuppro',
        'db_backups' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/database_backups',
        'file_backups' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/file_backups'
    );
    
    public static $browsers = array(
        array(
            'driver' => 'selenium2',
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://eric.ee2.clean.mithra62.com',
            'sessionStrategy' => 'shared',
        ),
    );
}