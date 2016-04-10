<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/RestApiKey.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Rest Api Key Validation Object
 *
 * Validates the Rest Api Key setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class RestApiKey extends DateFormat
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'api_key';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $enable_rest_api = $this->getData('enable_rest_api');
        if($enable_rest_api == 1)
        {
            $this->setupRule('required', '{field} is required');
        }
        return $this;
    }    
}