<?php
namespace mithra62\BackupPro\tests\Browser\WP;

use mithra62\BackupPro\tests\Browser\FreshInstallTestAbstract;
use mithra62\BackupPro\tests\Browser\WpTrait;

class FreshInstallTest extends FreshInstallTestAbstract
{
    use WpTrait;
    
    public $urls = array(
        'dashboard' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro',
        'db_backups' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2F&section=db_backups',
        'file_backups' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2F&section=file_backups'
    );
}