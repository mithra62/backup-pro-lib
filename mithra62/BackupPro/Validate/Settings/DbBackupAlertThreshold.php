<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DbBackupAlertThreshold.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Db Backup AlertThreshold Validation Object
 *
 * Validates the Db Backup AlertThreshold setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DbBackupAlertThreshold extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_backup_alert_threshold';
    
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