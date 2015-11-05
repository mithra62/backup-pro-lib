<?php
namespace mithra62\BackupPro\tests\Browser\EE3;

use mithra62\BackupPro\tests\Browser\FreshInstallTestAbstract;
use mithra62\BackupPro\tests\Browser\EE3Trait;

class FreshInstallTestXXX extends FreshInstallTestAbstract
{
    use EE3Trait;
    
    public $urls = array(
        'dashboard' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro',
        'db_backups' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/db_backups',
        'file_backups' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/file_backups',
        
        'db_backup' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/backup/database',
        'db_backup' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/backup/files',
        'settings' => array(
            'general' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/general',
            'db' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/db',
            'files' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/files',
            'cron' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/cron',
            'storage_locations' => array(
                'default' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/view_storage',
            ),
            'ia' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/integrity_agent',
            'license' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/settings/license',
        ),
    );
}