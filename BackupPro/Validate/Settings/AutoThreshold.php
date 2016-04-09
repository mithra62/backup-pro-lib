<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/AutoThreshold.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Storage Auto Threshold Validation Object
 *
 * Validates the Auto Threshold Total Setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class AutoThreshold extends AbstractField
{
    protected $field_name = 'auto_threshold';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::compileRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        if (! empty($data['auto_threshold']) && $data['auto_threshold'] == 'custom') {
            $this->setupRule('required', '{field} is required', false, 'auto_threshold_custom');
            $this->setupRule('numeric', '{field} must be a number', false, 'auto_threshold_custom');        
            if ($data['meta']['global']['total_space_used_raw'] > 100000000) {
                $this->setupRule('min', 'You\'re already using ' . $data['meta']['global']['total_space_used'] . ' so {field} must be at least "' . $data['meta']['global']['total_space_used_raw'] . '"', $data['meta']['global']['total_space_used_raw'], 'auto_threshold_custom');
            } else {
                $this->setupRule('min', '{field} must be at least 100MB (100000000)', 100000000, 'auto_threshold_custom');
            }
        } else {
            if ($data['auto_threshold'] != '0') {
                $this->setupRule('numeric', '{field} must be a number');
                $this->setupRule('min', 'You\'re already using ' . $data['meta']['global']['total_space_used'] . ' so {field} must be at least that or a custom value over "' . $data['meta']['global']['total_space_used_raw'] . '"', $data['meta']['global']['total_space_used_raw']);
            }
        }
        return $this;
    }
}