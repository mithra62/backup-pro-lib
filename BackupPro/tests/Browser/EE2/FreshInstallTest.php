<?php
namespace mithra62\BackupPro\tests\Browser\EE2;

use mithra62\BackupPro\tests\Browser\FreshInstallTestAbstract;
use mithra62\BackupPro\tests\Browser\EE2Trait;

class FreshInstallTest extends FreshInstallTestAbstract
{
    use EE2Trait;
    
    public $urls = array(
        'dashboard' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro',
        'db_backups' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=db_backups',
        'file_backups' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=file_backups'
    );
}