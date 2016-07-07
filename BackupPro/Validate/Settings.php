<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings.php
 */
namespace mithra62\BackupPro\Validate;

use mithra62\BackupPro\Validate;

/**
 * Backup Pro - Settings Validation Object
 *
 * Contains all the Validation rules for the Settings details
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class Settings extends Validate
{
    /**
     * The database object
     * @var mithra62\Db
     */
    protected $db = null;
    
    /**
     * The SQL Parser Object
     * @var PHPSQL\Parser
     */
    protected $sql_parser = null;
    
    /**
     * The previously set settings
     * 
     * @var array
     */
    protected $existing_settings = array();

    /**
     * Sets the previously set settings for the Validation object to use
     * 
     * @param array $settings            
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setExistingSettings(array $settings)
    {
        $this->existing_settings = $settings;
        return $this;
    }

    /**
     * Returns the previously set settings
     * 
     * @return array
     */
    public function getExistingSettings()
    {
        return $this->existing_settings;
    }
    
    /**
     * Sets the SQL Parser to use
     * @param \PHPSQL\Parser $parser
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setSqlParser(\PHPSQL\Parser $parser) 
    {
        $this->sql_parser = $parser;
        return $this;
    }
    
    /**
     * Returns the instance of the SQL Parser
     * @return \PHPSQL\Parser
     */
    public function getSqlParser() 
    {
        return $this->sql_parser;
    }

    /**
     * Sets the database object
     * 
     * @param \mithra62\Db $db           
     * @return \mithra62\BackupPro\Validate\Settings
     */
    public function setDb(\JaegerApp\Db $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Returns the db object
     * 
     * @return \mithra62\Db
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * Checks the entire settings array for issues
     * 
     * @param array $data            
     * @return bool
     */
    public function check(array $data, array $extra = array())
    {
        $fields = scandir(__DIR__.DIRECTORY_SEPARATOR.'Settings');
        $rules = array();
        if($fields) {
            foreach($fields AS $field)
            {
                if($field == '.' || $field == '..') {
                    continue;
                }
                
                $name = '\\mithra62\\BackupPro\\Validate\\Settings\\' . str_replace('/', '\\', str_replace('.php', '', $field));
                $class = $name;
                if (class_exists($class)) {
                    $rule = new $class($data, $extra);
                    if ($rule instanceof AbstractField) {
                        if(isset($data[$rule->getFieldName()]))
                        {
                            $val = $rule->setContext($this)->getRules();
                            if(is_array($val)) {
                                $rules = array_merge($rules, $val);
                            }
                        }
                    }
                }   
            }
        }
        
        if($rules) {
            foreach($rules AS $rule) 
            {
                $this->rule($rule['rule_name'], $rule['rule_field'], (isset($rule['rule_value']) ? $rule['rule_value'] : false))->message($rule['rule_message']);
            }
        }
        
        $this->val($data);
        if (! $this->hasErrors()) {
            return true;
        }
    }
}