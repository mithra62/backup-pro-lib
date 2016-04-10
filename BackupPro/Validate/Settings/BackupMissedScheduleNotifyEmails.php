<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/BackupMissedScheduleNotifyEmails.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Backup Missed Schedule Notify Emails Validation Object
 *
 * Validates the Backup Missed Schedule Notify Emails setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class BackupMissedScheduleNotifyEmails extends CronNotifyEmails
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'backup_missed_schedule_notify_emails';
}