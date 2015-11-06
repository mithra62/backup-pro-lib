<?php
namespace mithra62\BackupPro\tests\Browser\Craft;

use mithra62\BackupPro\tests\Browser\FreshInstallTestAbstract;
use mithra62\BackupPro\tests\Browser\CraftTrait;

class FreshInstallTest extends FreshInstallTestAbstract
{
    use CraftTrait;
    
    public $urls = array(
        'dashboard' => 'http://eric.craft.clean.mithra62.com/admin/backuppro',
        'db_backups' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/database_backups',
        'file_backups' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/file_backups',
        'db_backup' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/backup?type=database',
        'file_backup' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/backup?type=files',
    );
}