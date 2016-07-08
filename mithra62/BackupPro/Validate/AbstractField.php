<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/AbstractField.php
 */
namespace mithra62\BackupPro\Validate;

/**
 * Backup Pro - Settings Field Abstract Validation Object
 *
 * Outlines how a settings validation field should operate
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class AbstractField
{
    /**
     * The calling object
     * @var Settings
     */
    protected $context = null;
    
    /**
     * The name of the field we're validating
     * @var string
     */
    protected $field_name = '';
    
    /**
     * The data we're validating
     * @var array
     */
    protected $val_data = array();
    
    /**
     * Any extra data the validation requries
     * @var array
     */
    protected $val_data_extra = array();
    
    /**
     * The collection of rules we're setting up
     * @var array
     */
    protected $rules = array();
    
    /**
     * Set it up
     * @param array $data
     * @param array $extra
     */
    public function __construct(array $data = array(), array $extra = array())
    {
        $this->val_data = $data;
        $this->val_data_extra = $extra;
    }
    
    /**
     * Returns the rules
     * @return array
     */
    public function getRules()
    {
        $this->compileRules();
        return $this->rules;
    }
    
    /**
     * Sets an instance of the Validation object
     * @param Settings $context
     * @return \mithra62\BackupPro\Validate\AbstractField
     */
    public function setContext(Settings $context)
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * Returns the calling object
     * @return Settings
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Returns the field names
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }
    
    /**
     * Sets up a single rule for a field and returns the formatted array
     * @param string $rule_name
     * @param string $message
     * @param string $rule_value
     * @param string $field_name
     * @return \mithra62\BackupPro\Validate\AbstractField
     */
    protected function setupRule($rule_name, $message, $rule_value = false, $field_name = false)
    {
        $field_name = ($field_name === false ? $this->getFieldName() : $field_name);
        $this->rules[] = array('rule_name' => $rule_name, 'rule_field' => $field_name, 'rule_message' => $message, 'rule_value' => $rule_value);
        return $this;
    }
    
    /**
     * Returns a specific validation data key item
     * @param string $key
     * @return string
     */
    protected function getData($key = false)
    {
        if(!$key)
        {
            return $this->val_data;            
        }
        
        if(isset($this->val_data[$key]))
        {
            return $this->val_data[$key];
        }
    }
    
    /**
     * Returns the data the field we're working with expects
     * @return string
     */
    protected function getFieldData()
    {
        return $this->val_data[$this->getFieldName()];
    }

    /**
     * Sets up the validation rules
     * @return array
     */
    abstract public function compileRules();
}