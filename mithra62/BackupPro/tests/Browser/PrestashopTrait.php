<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/tests/Browser/PrestashopTrait.php
 */
namespace mithra62\BackupPro\tests\Browser;

use mithra62\Db;

/**
 * Backup Pro - Prestashop Trait
 *
 * Contains all the methods for using Selenium against Backup Pro and Prestashop
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
trait PrestashopTrait
{

    /**
     * The URLs to test the WordPress Settings page
     *
     * Note that the WordPress site MUST be configured to use cookies only for authentication
     * 
     * @var array
     */
    public $urls = array(
        
        // dashboards
        'dashboard' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProDashboard&token=83eed8c857c7501dc7219b412f3dc88a',
        'db_backups' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProDashboard&token=83eed8c857c7501dc7219b412f3dc88a&section=db_backups',
        'file_backups' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProDashboard&token=83eed8c857c7501dc7219b412f3dc88a&section=file_backups',
        
        // backup types
        'db_backup' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProBackupDatabase&token=d54c1cb538ea90ea55f538ec503fab4e',
        'file_backup' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProBackupFiles&token=1e1fb2c31ddba99bc20e2eba13b2333c',
        
        // settings
        'settings_general' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=general',
        'settings_db' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=db_backups',
        'settings_files' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=file_backups',
        'settings_cron' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=cron',
        'settings_ia' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=integrity_agent',
        'settings_license' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=license',
        
        // storage engines
        'storage_view_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage',
        'storage_add_email_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=email',
        'storage_add_ftp_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=ftp',
        'storage_add_gcs_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=gcs',
        'storage_add_local_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=local',
        'storage_add_rcf_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=rcf',
        'storage_add_s3storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=s3',
        'storage_add_dropbox_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=dropbox',
        'storage_add_sftp_storage' => 'http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php?controller=AdminBackupProSettings&token=0cf429d2f9ee73fe66d70b6bc3b7a9be&section=storage&sub=new_storage&engine=sftp'
    );

    /**
     * The various setting values we'll use for the tests
     * 
     * @var array
     */
    public $test_settings = array(
        'allowed_access_levels' => '',
        'auto_threshold' => '0',
        'auto_threshold_custom' => '',
        'exclude_paths' => array(),
        'allow_duplicates' => '0',
        
        'db_backup_on_cms_update' => '0',
        'file_backup_on_cms_update' => '0',
        'db_backup_on_plugin_update' => '0',
        'file_backup_on_plugin_update' => '0',
        'db_backup_on_plugin_install' => '0',
        'file_backup_on_plugin_install' => '0',
        'db_backup_on_theme_update' => '0',
        'file_backup_on_theme_update' => '0',
        'db_backup_on_theme_install' => '0',
        'file_backup_on_theme_install' => '0',
        
        'enable_cron_db_backup' => '1',
        'enable_cron_file_backup' => '1',
        'enable_cron_integrity_check' => '1',
        
        'cron_notify_emails' => array(),
        'cron_notify_email_subject' => '',
        'cron_notify_email_message' => '',
        'cron_notify_email_mailtype' => 'html',
        'cron_query_key' => 'yup', // the value the backup_pro query query key must have
        
        'storage_details' => array(),
        
        'working_directory' => 'D:\ProjectFiles\mithra62\clean_cms\prestashop\backup_meta',
        'local_backup_store_location' => 'D:\ProjectFiles\mithra62\clean_cms\prestashop\backups',
        'backup_file_location' => array(
            'D:\ProjectFiles\mithra62\clean_cms\prestashop\html'
        ),
        
        'max_file_backups' => '3',
        'max_db_backups' => '3',
        'db_backup_method' => 'php', // mysqldump
        'db_restore_method' => 'php', // mysql
        'db_backup_execute_pre_sql' => array(), // these get executed against MySQL before a backup starts
        'db_backup_execute_post_sql' => array(), // these get executed against MySQL after a backup finishes
        'db_backup_archive_pre_sql' => array(), // these get written in the backup SQL dump at the top
        'db_backup_archive_post_sql' => array(), // these get written in the backup SQL dump at the bottom
        'db_backup_ignore_tables' => array(), // what MySQL tables to ignore from the backup?
        'db_backup_ignore_table_data' => array(), // which tables should we not bother grabbing the data for?
        'mysqldump_command' => 'mysqldump',
        'mysqlcli_command' => 'mysql',
        'php_backup_method_select_chunk_limit' => '50',
        'system_command_function' => 'proc_open', // exec
        'dashboard_recent_total' => '5',
        'db_backup_alert_threshold' => '1',
        'file_backup_alert_threshold' => '7',
        
        'backup_missed_schedule_notify_emails' => array(),
        'backup_state_notify_email_subject' => '',
        'backup_state_notify_email_message' => '',
        'backup_state_notify_email_mailtype' => 'html',
        
        'backup_missed_schedule_notify_member_ids' => array(),
        'backup_missed_schedule_notify_email_mailtype' => 'html',
        'backup_missed_schedule_notify_email_subject' => '',
        'backup_missed_schedule_notify_email_message' => '',
        'backup_missed_schedule_notify_email_last_sent' => '0', // unix timestamp for determining whether to send an email
        'backup_missed_schedule_notify_email_interval' => 8, // hours between when backup state emails should be sent
        
        'db_verification_db_name' => '',
        'last_verification_type' => 'database', // either "database" or "files" to alternate between which backup to verify at any given time
        'last_verification_time' => '0', // timestamp last time a verification happened
        'total_verifications_per_execution' => '2', // the number of backups to check in a given run
        'check_backup_state_cp_login' => '1'
    );

    /**
     * Logs the user into the WordPress site
     */
    public function login()
    {
        // This is Mink's Session.
        $this->session = $this->getSession();
        
        // Go to a page.
        $this->session->visit('http://eric.prestashop.clean.mithra62.com/admin515exiszx/index.php');
        $this->session->maximizeWindow();
        
        // log in
        sleep(1);
        $page = $this->session->getPage();
        $page->findById('email')->setValue('eric@mithra62.com');
        $page->findById('passwd')->setValue('dimens35');
        $page->findButton('submitLogin')->submit();
    }

    /**
     * Helper method to handle logging in and installing the add-on
     */
    protected function setUp()
    {
        $this->login();
        $this->install_addon();
    }

    /**
     * Helper method to handle uinstalling the add-on
     */
    public function teardown()
    {
        $this->uninstall_addon();
    }

    /**
     * Installs the add-on
     */
    public function install_addon()
    {

    }

    /**
     * Uninstalls the add-on
     */
    public function uninstall_addon()
    {
        $db = new Db();
        $creds = $this->getDbCreds();
        $db->setCredentials($creds)
        ->setDbName('clean_presta')
        ->emptyTable('ps_backup_pro_settings');
        $db->setDbName($creds['database']);
    }
}
