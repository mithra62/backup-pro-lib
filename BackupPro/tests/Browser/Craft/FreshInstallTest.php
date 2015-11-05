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
        'db_backup' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/backup?type=files',
        'settings' => array(
            'general' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings',
            'db' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=db',
            'files' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=files',
            'cron' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=cron',
            'storage_locations' => array(
                'default' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings/storage',
            ),
            'ia' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=ia',
            'license' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=license',
        ),
    );
}