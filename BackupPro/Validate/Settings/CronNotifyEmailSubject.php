<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/CronNotifyEmails.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Cron Notify Emails Validation Object
 *
 * Validates the Cron Notify Emails setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class CronNotifyEmailSubject extends DateFormat
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'cron_notify_email_subject';
}