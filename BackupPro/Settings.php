<?php  
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Settings.php
 */
 
namespace mithra62\BackupPro;

use mithra62\Settings AS m62Settings;

/**
 * Backup Pro - Settings Object
 *
 * Object to manage system settings
 *
 * @package 	BackupPro
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Settings extends m62Settings
{
    /**
     * The name of the settings storage table
     * @var string
     */
    protected $table = 'backup_pro_settings';
    
    /**
     * The Validation object we're gonna use
     * @var \mithra62\BackupPro\Validate\Settings;
     */
    protected $validate = null;
    
    /**
     * The settings Backup Pro offers with a default value
     * @var array
     */
    protected $_defaults = array(
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
        'php_backup_method_select_chunk_limit' => 250,
        'system_command_function' => 'proc_open', //exec
        'regex_file_exclude' => '0',
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
     * The settings keys that should be serialized for storage
     * @var array
     */
    protected $serialized = array(
        'db_backup_ignore_tables',
        'db_backup_ignore_table_data',
        'storage_details'
    );    
	
	/**
	 * The settings keys that should be encrypted for storage
	 * @var array
	 */
	protected $encrypted = array(
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
	protected $new_lines = array(
        'exclude_paths',
	    'cron_notify_emails',
        'backup_file_location',
        'cron_notify_emails',
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
	protected $auto_prune_threshold_options = array(
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
	 * Sets up the object
	 * @param \mithra62\Db $db
	 * @param \mithra62\Language $lang
	 */
    public function __construct(\mithra62\Db $db, \mithra62\Language $lang)
    {
        parent::__construct($db, $lang);
        $this->setTable($this->table);
        $this->_defaults['working_directory'] = realpath(dirname(realpath(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'backups');
        $this->_defaults['cron_notify_email_message'] = $this->lang->__('default_cron_message');
        $this->_defaults['cron_notify_email_subject'] = $this->lang->__('default_cron_subject');
        $this->_defaults['backup_missed_schedule_notify_email_subject'] = $this->lang->__('default_backup_missed_schedule_notify_email_subject');
        $this->_defaults['backup_missed_schedule_notify_email_message'] = $this->lang->__('default_backup_missed_schedule_notify_email_message');        
        
        $this->setDefaults($this->_defaults);
    } 
    
    /**
     * Returns the auto prune threhsold value options
     * @return array
     */
    public function getAutoPruneThresholdOptions()
    {
        return $this->auto_prune_threshold_options;
    }
    
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\Settings::validate()
     */
    public function validate(array $data, array $extra = array())
    {
        $errors = array();
        if( !$this->getValidate()->setExistingSettings( $this->get() )->check($data, $extra) )
        {
            $errors = $this->getValidate()->getErrorMessages();
        }
        
        return $errors;
    }
    
    /**
     * Returns an instance of the Validation object
     * @return \mithra62\BackupPro\Validate\Settings;
     */
    private function getValidate()
    {
       return $this->validate;
    }
    
    /**
     * Sets the Validation object
     * @param \mithra62\Validate $validate
     * @return \mithra62\BackupPro\Settings
     */
    public function setValidate(\mithra62\Validate $validate)
    {
        $this->validate = $validate;
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\Settings::get()
     */
    public function get($force = false)
    {
        $this->settings = parent::get($force);
        if($this->settings['backup_file_location'] == '')
        {
            $this->settings['backup_file_location'] = $this->_defaults['backup_file_location'];
        }
        
        if($this->settings['working_directory'] == '')
        {
            $this->settings['working_directory'] = $this->_defaults['working_directory'];
        }
        
        //little sanity check to ensure we can use the `system()` function for SQL backups and set to PHP accordingly
        if( !function_exists('system') )
        {
            $this->settings['db_backup_method'] = $this->settings['db_restore_method'] = 'php';
        }
        
        $this->settings['max_file_backups'] = (int)$this->settings['max_file_backups'];
        $this->settings['max_db_backups'] = (int)$this->settings['max_db_backups'];
        
        //we have to ensure storage_details is always an array since if the encryption key is changed we'll get a bad return. Rare, but it happens.
        $this->settings['storage_details'] = (is_array($this->settings['storage_details']) ? $this->settings['storage_details'] : array());

        
        return $this->settings;
    }    
}