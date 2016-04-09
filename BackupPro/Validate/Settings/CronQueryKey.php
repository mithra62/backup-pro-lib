<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/CronQueryKey.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Cron Query Key Validation Object
 *
 * Validates the Cron Query Key setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class CronQueryKey extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'cron_query_key';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $this->setupRule('required', '{field} is required');
        $this->setupRule('alphanum', '{field} must be alpha-numeric only');
        return $this;
    }
}