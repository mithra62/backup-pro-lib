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
        'file_backups' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2F&section=file_backups',
        
        'db_backup' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fconfirm_backup_db',
        'file_backup' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fconfirm_backup_files',
        'settings' => array(
            'general' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings',
            'db' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=db',
            'files' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=files',
            'cron' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=cron',
            'storage_locations' => array(
                'default' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=storage',
            ),
            'ia' => 'http://eric.wp.clean.mithra62.com/wp-admin/admin.php?page=backup_pro%2Fsettings&section=integrity_agent',
            'license' => 'http://eric.craft.clean.mithra62.com/admin/backuppro/settings?section=license',
        ),
    );
    
}