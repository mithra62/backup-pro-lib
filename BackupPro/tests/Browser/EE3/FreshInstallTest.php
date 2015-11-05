<?php
namespace mithra62\BackupPro\tests\Browser\EE3;

use mithra62\BackupPro\tests\Browser\FreshInstallTestAbstract;
use mithra62\BackupPro\tests\Browser\EE3Trait;

class FreshInstallTest extends FreshInstallTestAbstract
{
    use EE3Trait;
    
    public $urls = array(
        'dashboard' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro',
        'db_backups' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/db_backups',
        'file_backups' => 'http://eric.ee3.clean.mithra62.com/admin.php?/cp/addons/settings/backup_pro/file_backups'
    );
}