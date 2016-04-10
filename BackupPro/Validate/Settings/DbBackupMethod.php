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
class DbBackupMethod extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_backup_method';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        $field_data = $this->getFieldData();
        if (! empty($field_data) && $field_data == 'mysqldump') {
            $this->setupRule('required', '{field} is required', false, 'mysqldump_command');
        }
        return $this;
    }
}