<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/WorkingDirectory.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Working Directory Validation Object
 *
 * Contains all the Validation rules for the Settings details
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class WorkingDirectory extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'working_directory';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::compileRules()
     */
    public function compileRules()
    {
        $data = $this->getData();
        $path = $data['working_directory'];
        
        // ensure we don't have a local storage location setup for this path already
        $existing_settings = $this->getContext()->getExistingSettings();
        if (! empty($existing_settings['storage_details']) && is_array($existing_settings['storage_details'])) {
            foreach ($existing_settings['storage_details'] as $key => $value) {
                if ($value['storage_location_driver'] == 'local' && $value['backup_store_location'] == $path) {
                    $this->setupRule('false', '{field} can\'t be set as a Local Storage path');
                    break;
                }
            }
        }
        
        if ($existing_settings['working_directory'] != $path) {
            $this->setupRule('dir_empty', '{field} has to be an empty directory');
            
        }
        $this->setupRule('required', '{field} is required');
        $this->setupRule('writable', '{field} has to be writable');
        $this->setupRule('dir', '{field} has to be a directory');
        $this->setupRule('readable', '{field} has to be readable');
        
        return $this;
    }
}