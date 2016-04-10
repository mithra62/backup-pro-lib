<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/RestApiSecret.php
 */
namespace mithra62\BackupPro\Validate\Settings;

/**
 * Backup Pro - Rest Api Secret Validation Object
 *
 * Validates the Rest Api Secret setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class RestApiSecret extends RestApiKey
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'api_secret';   
}