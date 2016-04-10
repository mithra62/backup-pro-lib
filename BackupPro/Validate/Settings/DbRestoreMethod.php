<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DbRestoreMethod.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Db Restore Method Validation Object
 *
 * Validates the Db Restore Method Threshold setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DbRestoreMethod extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_restore_method';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        $field_data = $this->getFieldData();
        if (! empty($field_data) && $field_data == 'mysql') {
            $this->setupRule('required', '{field} is required', false, 'mysqlcli_command');
        }
        return $this;
    }
}