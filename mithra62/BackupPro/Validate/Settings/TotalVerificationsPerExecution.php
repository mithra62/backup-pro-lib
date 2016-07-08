<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/MaxDbBackups.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Total Verifications Per Execution Validation Object
 *
 * Validates the Total Verifications Per Execution setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class TotalVerificationsPerExecution extends MaxDbBackups
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'total_verifications_per_execution';
}