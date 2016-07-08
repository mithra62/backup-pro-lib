<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DbVerificationDbName.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Db Verification Db Name Validation Object
 *
 * Validates the Db Verification Db Name setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DbVerificationDbName extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_verification_db_name';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::compileRules()
     */
    public function compileRules()
    {
        $credentials = $this->val_data_extra['db_creds'];
        $name = $this->getFieldData();
        if ($name != '') {
            if ($name == $credentials['database']) {
                $this->setupRule('false', 'db_verification_db_name')->message('"' . $name . '" is the site db; you can\'t use that for verification');
            } else {
                try {
        
                    if( !$this->getContext()->getDb()->checkDbExists($name) )
                    {
                        $this->setupRule('false', '"' . $name . '" isn\'t available to your configured database connection');
                    }
                    else
                    {
        
                        $tables = $this->getContext()->getDb()->setDbName($name)->getTables();
                        if (count($tables) != '0') {
                            $this->setupRule('false', '"' . $name . '" isn\'t an empty database; remove all the tables and try again.');
                        }
        
                        $this->getContext()->getDb()->setDbName($credentials['database']); //set back to the main db
                    }
        
                } catch (\PDOException $e) {
                    $this->setupRule('false', '"' . $name . '" isn\'t available to your configured database connection');
                }
            }
        }
        
        return $this;
    }
}