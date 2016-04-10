<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/DbBackupArchivePreSql.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Db Backup Archive Pre Sql Validation Object
 *
 * Validates the Db Backup Archive Pre Sql setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class DbBackupArchivePreSql extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'db_backup_archive_pre_sql';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::getRules()
     */
    public function compileRules()
    {
        $statements = $this->getFieldData();
        if(!$statements) {
            return $this;
        }
        if (! is_array($statements)) {
            $statements = explode("\n", $statements);
        }
        
        foreach ($statements as $statement) {
            $path = trim($statement);
            $parts = $this->getContext()->getSqlParser()->parse($statement);
        
            if (! $parts ) {
                $this->setupRule('false', '"' . $statement . '" isn\'t a valid SQL statement');
            }
        }
        
        return $this;
    }
}