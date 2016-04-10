<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings.php
 */
namespace mithra62\BackupPro\Validate;

use mithra62\BackupPro\Validate;

/**
 * Backup Pro - Settings Validation Object
 *
 * Contains all the Validation rules for the Settings details
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class Settings extends Validate
{
    /**
     * The database object
     * @var mithra62\Db
     */
    protected $db = null;
    
    /**
     * The SQL Parser Object
     * @var PHPSQL\Parser
     */
    protected $sql_parser = null;
    
    /**
     * The previously set settings
     * 
     * @var array
     */
    protected $existing_settings = array();

    /**
     * Sets the previously set settings for the Validation object to use
     * 
     * @param array $settings            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setExistingSettings(array $settings)
    {
        $this->existing_settings = $settings;
        return $this;
    }

    /**
     * Returns the previously set settings
     * 
     * @return array
     */
    public function getExistingSettings()
    {
        return $this->existing_settings;
    }
    
    /**
     * Sets the SQL Parser to use
     * @param \PHPSQL\Parser $parser
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setSqlParser(\PHPSQL\Parser $parser) 
    {
        $this->sql_parser = $parser;
        return $this;
    }
    
    /**
     * Returns the instance of the SQL Parser
     * @return \PHPSQL\Parser
     */
    public function getSqlParser() 
    {
        return $this->sql_parser;
    }

    /**
     * Sets the database object
     * 
     * @param \mithra62\Db $db           
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setDb(\mithra62\Db $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Returns the db object
     * 
     * @return \mithra62\Db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Validates a license number
     * 
     * @param array $data            
     */
    public function licenseNumber(array $data)
    {
        $this->rule('required', 'license_number')->message('{field} is required');
        $this->rule('license_key', 'license_number')->message('{field} isn\'t a valid license key');
    }

    /**
     * Validates the file backup location setting
     * 
     * @param string $emails            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function cronNotifyEmails($emails)
    {
        if ($emails != '') {
            if (! is_array($emails)) {
                $emails = explode("\n", $emails);
            }
            
            foreach ($emails as $email) {
                if (! filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                    $this->rule('false', 'cron_notify_emails')->message('"' . trim($email) . '" isn\'t a valid email');
                    // break;
                }
            }
        }
        
        return $this;
    }

    /**
     * Validates we have a subject for cron notify emails
     * 
     * @param string $subject            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function cronNotifyEmailSubject($subject)
    {
        if ($subject == '') {
            $this->rule('required', 'cron_notify_email_subject')->message('{field} is required');
        }
        
        return $this;
    }

    /**
     * Validates we have a message for cron notify emails
     * 
     * @param string $message            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function cronNotifyEmailMessage($message)
    {
        if ($message == '') {
            $this->rule('required', 'cron_notify_email_message')->message('{field} is required');
        }
        
        return $this;
    }

    /**
     * Validates the file backup location setting
     * 
     * @param string $emails            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmail($emails)
    {
        if ($emails != '') {
            if (! is_array($emails)) {
                $emails = explode("\n", $emails);
            }
            
            foreach ($emails as $email) {
                if (! filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                    $this->rule('false', 'backup_missed_schedule_notify_emails')->message('"' . trim($email) . '" isn\'t a valid email');
                    // break;
                }
            }
        }
        
        return $this;
    }

    /**
     * Validates the subject for the missed backup email
     * 
     * @param string $subject            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmailSubject($subject)
    {
        if ($subject == '') {
            $this->rule('required', 'backup_missed_schedule_notify_email_subject')->message('{field} is required');
        }
        
        return $this;
    }

    /**
     * Validates the message for the missed backup email
     * 
     * @param string $message            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmailMessage($message)
    {
        if ($message == '') {
            $this->rule('required', 'backup_missed_schedule_notify_email_message')->message('{field} is required');
        }
        
        return $this;
    }

    /**
     * Validates the total verifications per execution value
     * 
     * @param string $message            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function totalVerificationsPerExecution($total)
    {
        if ($total == '') {
            $this->rule('required', 'total_verifications_per_execution')->message('{field} is required');
        }
        
        $this->rule('integer', 'total_verifications_per_execution')->message('{field} must be a whole number');
        
        return $this;
    }

    /**
     * Validates the total verifications per execution value for the missed backup email
     * 
     * @param number $total            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function backupMissedScheduleNotifyEmailInterval($total)
    {
        if ($total == '') {
            $this->rule('required', 'backup_missed_schedule_notify_email_interval')->message('{field} is required');
        }
        
        $this->rule('integer', 'backup_missed_schedule_notify_email_interval')->message('{field} must be a whole number');
        
        return $this;
    }

    /**
     * Validates the database name for the Integrity Agent
     * 
     * @param string $name
     *            The database name to validate
     * @param array $credentials
     *            The connection details
     */
    public function dbVerificationDbName($name, array $credentials)
    {
        if ($name != '') {
            if ($name == $credentials['database']) {
                $this->rule('false', 'db_verification_db_name')->message('"' . $name . '" is the site db; you can\'t use that for verification');
            } else {
                try {
                    
                    if( !$this->getDb()->checkDbExists($name) )
                    {
                        $this->rule('false', 'db_verification_db_name')->message('"' . $name . '" isn\'t available to your configured database connection');
                    }
                    else
                    {
                        
                        $tables = $this->getDb()->setDbName($name)->getTables();
                        if (count($tables) != '0') {
                            $this->rule('false', 'db_verification_db_name')->message('"' . $name . '" isn\'t an empty database; remove all the tables and try again.');
                        }
                        
                        $this->getDb()->setDbName($credentials['database']); //set back to the main db
                    }
                    
                } catch (\PDOException $e) {
                    $this->rule('false', 'db_verification_db_name')->message('"' . $name . '" isn\'t available to your configured database connection');
                }
            }
        }
    }

    /**
     * Checks the entire settings array for issues
     * 
     * @param array $data            
     * @return bool
     */
    public function check(array $data, array $extra = array())
    {
        $fields = scandir(__DIR__.DIRECTORY_SEPARATOR.'Settings');
        $rules = array();
        if($fields) {
            foreach($fields AS $field)
            {
                if($field == '.' || $field == '..') {
                    continue;
                }
                
                $name = '\\mithra62\\BackupPro\\Validate\\Settings\\' . str_replace('/', '\\', str_replace('.php', '', $field));
                $class = $name;
                if (class_exists($class)) {
                    $rule = new $class($data, $extra);
                    if ($rule instanceof AbstractField) {
                        if(isset($data[$rule->getFieldName()]))
                        {
                            $val = $rule->setContext($this)->getRules();
                            if(is_array($val)) {
                                $rules = array_merge($rules, $val);
                            }
                        }
                    }
                }   
            }
        }
        
        if($rules) {
            foreach($rules AS $rule) 
            {
                $this->rule($rule['rule_name'], $rule['rule_field'], (isset($rule['rule_value']) ? $rule['rule_value'] : false))->message($rule['rule_message']);
            }
        }
        
        if (isset($data['license_number'])) {
            $this->licenseNumber($data);
        }
        
        if (isset($data['cron_notify_emails'])) {
            $this->cronNotifyEmails($data['cron_notify_emails']);
        }
        
        if (isset($data['cron_notify_email_subject'])) {
            $this->cronNotifyEmailSubject($data['cron_notify_email_subject']);
        }
        
        if (isset($data['cron_notify_email_message'])) {
            $this->cronNotifyEmailMessage($data['cron_notify_email_message']);
        }
        
        if (isset($data['backup_missed_schedule_notify_emails'])) {
            $this->backupMissedScheduleNotifyEmail($data['backup_missed_schedule_notify_emails']);
        }
        
        if (isset($data['backup_missed_schedule_notify_email_subject'])) {
            $this->backupMissedScheduleNotifyEmailSubject($data['backup_missed_schedule_notify_email_subject']);
        }
        
        if (isset($data['backup_missed_schedule_notify_email_message'])) {
            $this->backupMissedScheduleNotifyEmailMessage($data['backup_missed_schedule_notify_email_message']);
        }
        
        if (isset($data['total_verifications_per_execution'])) {
            $this->totalVerificationsPerExecution($data['total_verifications_per_execution']);
        }
        
        if (isset($data['backup_missed_schedule_notify_email_interval'])) {
            $this->backupMissedScheduleNotifyEmailInterval($data['backup_missed_schedule_notify_email_interval']);
        }
        
        if (isset($data['db_verification_db_name'])) {
            $this->dbVerificationDbName($data['db_verification_db_name'], $extra['db_creds']);
        }
        
        if( isset($data['db_backup_archive_post_sql']) ) {
            //$this->dbBackupArchivePreSql($data['db_backup_archive_post_sql'], 'db_backup_archive_post_sql');
        }
        
        if( isset($data['db_backup_execute_pre_sql']) ) {
            //$this->dbBackupArchivePreSql($data['db_backup_execute_pre_sql'], 'db_backup_execute_pre_sql');
        }
        
        if( isset($data['db_backup_execute_post_sql']) ) {
            ///$this->dbBackupArchivePreSql($data['db_backup_execute_post_sql'], 'db_backup_execute_post_sql');
        }
        
        $this->val($data);
        if (! $this->hasErrors()) {
            return true;
        }
    }
}