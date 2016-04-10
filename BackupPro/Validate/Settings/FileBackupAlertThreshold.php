<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/FileBackupAlertThreshold.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - File Backup Alert Threshold Validation Object
 *
 * Validates the File Backup Alert Threshold setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class FileBackupAlertThreshold extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'file_backup_alert_threshold';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        $this->setupRule('numeric', '{field} must be a number only');
        return $this;
    }
}