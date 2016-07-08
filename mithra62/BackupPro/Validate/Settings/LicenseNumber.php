<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/LicenseNumber.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - License Number Validation Object
 *
 * Validates the License Number setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class LicenseNumber extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'license_number';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::compileRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        $this->setupRule('license_key', '{field} isn\'t a valid license key');
        return $this;
    }
}