<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Backup/Database/Php/Columns/Longblob.php
 */
 
namespace mithra62\BackupPro\Backup\Database\Engines\Php\Columns;

use mithra62\BackupPro\Backup\Database\Engines\Php\Columns;

/**
 * Backup Pro - Longblob Column Object
 *
 * Handles processing data from a MySQL Longblob column
 *
 * @package 	Backup\Database\Engines\Php
 * @author		Eric Lamb <eric@mithra62.com>
 */
class Blob extends Columns
{
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\BackupPro\Backup\Database\Engines\Php\Columns::getFieldName()
     */
    public function getFieldName(array $column)
    {
        return '`'.$column['Field'].'`';
    }
    
    /**
     * (non-PHPdoc)
     * @ignore
     * @see \mithra62\BackupPro\Backup\Database\Engines\Php\Columns::getFieldValue()
     */
    public function getFieldValue($value)
    {
        return $this->fromBase64Val($value);
    }
}