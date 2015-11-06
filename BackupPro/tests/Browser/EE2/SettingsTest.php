<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/EE2/SettingsTest.php
 */
 
namespace mithra62\BackupPro\tests\Browser\EE2;

use mithra62\BackupPro\tests\Browser\SettingsTestAbstract;
use mithra62\BackupPro\tests\Browser\EE2Trait;
 
/**
 * mithra62 - ExpressionEngine 2 Browser (Selenium) Settings object Unit Tests
 *
 * Defines the values the Settings Selenium tests will use to execute the test
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
class SettingsTest extends SettingsTestAbstract 
{
    use EE2Trait;
    
    /**
     * The URLs to test the ExpressionEngine 2 Settings page
     * 
     * Note that the ExpressionEngine 2 site MUST be configured to use cookies only for authentication
     * @var array
     */
    public $urls = array(
        'settings_general' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=general',
        'settings_db' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=db',
        'settings_files' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=files',
        'settings_cron' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=cron',
        'settings_ia' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=integrity_agent',
        'settings_license' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=settings&section=license',
    );
    
    /**
     * The various setting values we'll use for the tests
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
        'cron_query_key' => 'yup', //the value the backup_pro query query key must have
        
        'storage_details' => array(),
        
        'working_directory' => 'D:\ProjectFiles\mithra62\clean_cms\ee2\backup_meta',
        'backup_file_location' => array('D:\ProjectFiles\mithra62\clean_cms\ee2\html'),
        
        'max_file_backups' => '3',
        'max_db_backups' => '3',
        'db_backup_method' => 'php', //mysqldump
        'db_restore_method' => 'php', //mysql
        'db_backup_execute_pre_sql' => array(), //these get executed against MySQL before a backup starts
        'db_backup_execute_post_sql' => array(), //these get executed against MySQL after a backup finishes
        'db_backup_archive_pre_sql' => array(), //these get written in the backup SQL dump at the top
        'db_backup_archive_post_sql' => array(), //these get written in the backup SQL dump at the bottom
        'db_backup_ignore_tables' => array(), //what MySQL tables to ignore from the backup?
        'db_backup_ignore_table_data' => array(), //which tables should we not bother grabbing the data for?
        'mysqldump_command' => 'mysqldump',
        'mysqlcli_command' => 'mysql',
        'php_backup_method_select_chunk_limit' => '50',
        'system_command_function' => 'proc_open', //exec
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
        'backup_missed_schedule_notify_email_last_sent' => '0', //unix timestamp for determining whether to send an email
        'backup_missed_schedule_notify_email_interval' => 8, //hours between when backup state emails should be sent
    
        'db_verification_db_name' => '',
        'last_verification_type' => 'database', // either "database" or "files" to alternate between which backup to verify at any given time
        'last_verification_time' => '0', //timestamp last time a verification happened
        'total_verifications_per_execution' => '2', //the number of backups to check in a given run
        'check_backup_state_cp_login' => '1',
    );
    
    /**
     * Disable this since we want full browser support
     */
    public function setUp()
    {
        
    }
    
    /**
     * Disable this since we want full browser support
     */
    public function teardown()
    {
        
    }
}