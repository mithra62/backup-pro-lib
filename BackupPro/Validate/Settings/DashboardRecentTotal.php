<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DashboardRecentTotal.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Dashboard Recent Total Validation Object
 *
 * Validates the Dashboard Recent Total setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DashboardRecentTotal extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'dashboard_recent_total';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        $this->setupRule('min', '{field} must be a number greater than 1', 1);
        return $this;
    }
}