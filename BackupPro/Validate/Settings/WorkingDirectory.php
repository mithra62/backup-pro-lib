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
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::compileRules()
     */
    public function compileRules()
    {
        $path = $data['working_directory'];
        $rules = array();
        
        // ensure we don't have a local storage location setup for this path already
        $existing_settings = $this->getContext()->getExistingSettings();
        if (! empty($existing_settings['storage_details']) && is_array($existing_settings['storage_details'])) {
            foreach ($existing_settings['storage_details'] as $key => $value) {
                if ($value['storage_location_driver'] == 'local' && $value['backup_store_location'] == $path) {
                    $rules[] = array(
                        'rule_name' => 'false',
                        'rule_field' => 'working_directory',
                        'rule_message' => '{field} can\'t be set as a Local Storage path'
                    );
                    break;
                }
            }
        }
        
        if ($existing_settings['working_directory'] != $path) {
            $rules[] = array(
                'rule_name' => 'dir_empty',
                'rule_field' => 'working_directory',
                'rule_message' => '{field} has to be an empty directory'
            );
        }
        
        $rules[] = array(
            'rule_name' => 'required',
            'rule_field' => 'working_directory',
            'rule_message' => '{field} is required'
        );
        $rules[] = array(
            'rule_name' => 'writable',
            'rule_field' => 'working_directory',
            'rule_message' => '{field} has to be writable'
        );
        $rules[] = array(
            'rule_name' => 'dir',
            'rule_field' => 'working_directory',
            'rule_message' => '{field} has to be a directory'
        );
        $rules[] = array(
            'rule_name' => 'readable',
            'rule_field' => 'working_directory',
            'rule_message' => '{field} has to be readable'
        );
        
        return $rules;
    }
}