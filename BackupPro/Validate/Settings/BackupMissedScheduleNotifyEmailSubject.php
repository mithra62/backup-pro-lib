<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/BackupMissedScheduleNotifyEmailSubject.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Backup Missed Schedule Notify Email Subject Validation Object
 *
 * Validates the Backup Missed Schedule Notify Email Subject setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class BackupMissedScheduleNotifyEmailSubject extends DateFormat
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'backup_missed_schedule_notify_email_subject';
}