<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/ExcludePaths.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Exclude Paths Validation Object
 *
 * Validates the Exclude Paths setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class ExcludePaths extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'exclude_paths';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $paths = $this->getFieldData();
        if (! is_array($paths)) {
            $paths = explode("\n", $paths);
        }
        
        foreach ($paths as $path) {
            $path = trim($path);
            if (! $this->getContext()->getRegex()->validate($path)) {
                if (file_exists($path)) {
                    if (! is_readable($path)) {
                        $this->setupRule('false', '"' . $path . '" isn\'t a readable path by PHP.');
                    }
                } else {
                    $this->setupRule('false', '"' . $path . '" isn\'t a valid regular expression or path on the system.');
                }
            }
        }        
    }
}