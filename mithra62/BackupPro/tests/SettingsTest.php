<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/SettingsTest.php
 */
namespace mithra62\BackupPro\tests;

use mithra62\BackupPro\Settings;
use JaegerApp\Db;
use JaegerApp\Language;
use mithra62\BackupPro\tests\TestFixture;

/**
 * mithra62 - Settings object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Settings object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class SettingsTest extends TestFixture
{

    /**
     * The name of the settings storage table
     * 
     * @var string
     */
    protected $expected_settings_table = 'backup_pro_settings';

    /**
     * These setting keys are ignored since they're already tested elsewhere
     * 
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
     * 
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
     * 
     * @var array
     */
    protected $expected_encrypted = array(
        'storage_details'
    );

    /**
     * The settings keys that have a custom option available
     * 
     * @var array
     */
    protected $custom_options = array(
        'auto_threshold'
    );

    /**
     * The settings keys that are sent in new line dilemiter
     * 
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
     * 
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
        $settings = new Settings(new Db(), new Language());
        $this->assertObjectHasAttribute('auto_prune_threshold_options', $settings);
        
        $this->assertTrue(is_array($settings->getDefaults()));
        $this->assertCount(69, $settings->getDefaults());
        
        $this->assertTrue(is_array($settings->getCustomOptions()));
        $this->assertCount(1, $settings->getCustomOptions());
        
        $this->assertTrue(is_array($settings->getOverrides()));
        $this->assertCount(0, $settings->getOverrides());
        
        $this->assertTrue(is_array($settings->getEncrypted()));
        $this->assertCount(3, $settings->getEncrypted());
        
        $this->assertTrue($settings->getTable() == $this->expected_settings_table);
    }

    public function testDefaultWorkingDirValue()
    {
        $settings = new Settings(new Db(), new Language());
        $defaults = $settings->getDefaults();
        // $this->assertTrue( is_dir($defaults['working_dir']) );
    }
}