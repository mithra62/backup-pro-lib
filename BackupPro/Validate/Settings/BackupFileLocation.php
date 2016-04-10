<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/BackupFileLocation.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Backup File Location Validation Object
 *
 * Validates the Backup File Location setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class BackupFileLocation extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'backup_file_location';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $paths = $this->getFieldData();
        if ($paths == '') {
            $this->setupRule('required', '{field} is required');
        } else {
            if (! is_array($paths)) {
                $paths = explode("\n", $paths);
            }
        
            foreach ($paths as $path) {
                $path = trim($path);
                if (file_exists($path)) {
                    if (! is_readable($path)) {
                        $this->setupRule('false', '"' . $path . '" isn\'t a readable path by PHP.');
                    }
                } else {
                    $this->setupRule('false', '"' . $path . '" isn\'t a valid path on the system.');
                }
                
            }
        } 
        return $this;       
    }
}