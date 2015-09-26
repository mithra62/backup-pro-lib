<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database/Php/Columns.php
 */
 
namespace mithra62\BackupPro\Backup\Database\Engines\Php;

/**
 * Backup Pro - PHP Backup Engine
 *
 * Performs a backup using native PHP
 *
 * @package 	Backup\Database\Engines
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class Columns
{
    /**
     * Creates the unique column name to use for the specific column type
     * @param array $column
     * @return string
     */
    abstract function getFieldName(array $column);
    
    /**
     * Creates the value to use in the SQL statement for the backup based on the column type
     * @param string $value
     */
    abstract function getFieldValue($value);
    
    /**
     * Wraps the field name in the MySQL AsText() function
     * @param string $field_name
     * @return string
     */
    public function asText($field_name)
    {
        return 'AsText('.$field_name.') AS '.$field_name;
    }
}