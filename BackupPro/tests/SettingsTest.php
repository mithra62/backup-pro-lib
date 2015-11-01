<?php
/**
 * mithra62 - Unit Test
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/SettingsTest.php
 */

namespace mithra62\BackupPro\tests;

use mithra62\BackupPro\Settings;
use mithra62\Db;
use mithra62\Language;
use mithra62\tests\TestFixture;


/**
 * mithra62 - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Settings object
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
class SettingsTest extends TestFixture
{
    /**
     * The name of the settings storage table
     * @var string
     */
    protected $expected_settings_table = 'backup_pro_settings';
    
    /**
     * The settings Backup Pro offers with a default value
     * @var array
     */
    protected $expected_defaults = array(
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
        
        'working_directory' => '',
        'backup_file_location' => array(),
        
        'max_file_backups' => '0',
        'max_db_backups' => '0',
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
     * These setting keys are ignored since they're already tested elsewhere
     * @var array
     */
    private $lang_overrides_to_ignore = array(
        'cron_notify_email_message', 
        'cron_notify_email_subject', 
        'backup_missed_schedule_notify_email_subject', 
        'backup_missed_schedule_notify_email_message',
        'working_directory'
    );
    
    /**
     * The settings keys that should be serialized for storage
     * @var array
    */
    protected $expected_serialized = array(
        'cron_notify_emails',
        'backup_missed_schedule_notify_member_ids',
        'db_backup_ignore_tables',
        'db_backup_ignore_table_data',
        'storage_details'
    );
    
    /**
     * The settings keys that should be encrypted for storage
     * @var array
    */
    protected $expected_encrypted = array(
        'storage_details'
    );
    
    
    /**
     * The settings keys that have a custom option available
     * @var array
    */
    protected $custom_options = array(
        'auto_threshold',
    );
    
    /**
     * The settings keys that are sent in new line dilemiter
     * @var array
    */
    protected $expected_new_lines = array(
        'exclude_paths',
        'cron_notify_emails',
        'backup_file_location',
        'db_backup_execute_pre_sql',
        'db_backup_execute_post_sql',
        'db_backup_archive_pre_sql',
        'db_backup_archive_post_sql',
        'backup_missed_schedule_notify_emails'
    );
    
    /**
     * The options available for scheduling
     * @var array
    */
    protected $expected_auto_prune_threshold_options = array(
        '0' => 'Disabled',
        '104857600' => '100MB',
        '262144000' => '250MB',
        '524288000' => '500MB',
        '786432000' => '750MB',
        '1073741824' => '1GB',
        '5368709120' => '5GB',
        '10737418240' => '10GB',
        'custom' => 'Custom'
    );
    /**
     * Tests the initial attributes and property values
     */
    public function testInit()
    {
        $settings = new Settings(new Db, new Language );
        $this->assertObjectHasAttribute('auto_prune_threshold_options', $settings);
        
        $this->assertTrue(is_array($settings->getDefaults()));
        $this->assertCount(63, $settings->getDefaults());

        $this->assertTrue(is_array($settings->getCustomOptions()));
        $this->assertCount(1, $settings->getCustomOptions());

        $this->assertTrue(is_array($settings->getOverrides()));
        $this->assertCount(0, $settings->getOverrides());

        $this->assertTrue(is_array($settings->getEncrypted()));
        $this->assertCount(1, $settings->getEncrypted());
        
        $this->assertTrue($settings->getTable() == $this->expected_settings_table);
    }
    
    public function testDefaults()
    {
        $settings = new Settings(new Db, new Language );
        $defaults = $settings->getDefaults();
        foreach($this->expected_defaults AS $key => $value)
        {
            $this->assertArrayHasKey($key, $defaults);
            if( !in_array($key, $this->lang_overrides_to_ignore) )
            {
                $this->assertSame($defaults[$key], $value, 'Failed asserting default setting '.$key.' had a value of: '.$value);
            }
        }
    }
}