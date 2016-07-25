<?php
/**
 * mithra62 - Backup Pro
 * 
 * Console Unit Tests
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/ConsoleTest.php
 */
namespace mithra62\BackupPro\tests\Backup\Database\Engines\Php;

use mithra62\BackupPro\Backup\Database\Engines\Php\Columns;
use mithra62\BackupPro\tests\TestFixture;

class _testColumns extends Columns
{
    public function getFieldName(array $column)
    {
        
    }
    
    public function getFieldValue($value)
    {
        
    }
}

class ColumnsTest extends TestFixture
{
    public function testAsTextReturnValue()
    {
        $column_name = 'test_field_name';
        $column = new _testColumns();
        $value = $column->asTextCol($column_name);
        $this->assertEquals('AsText('.$column_name.') AS '.$column_name, $value);
    }
    
    public function testToBase64Col()
    {
        $column_name = 'test_field_name';
        $column = new _testColumns();
        $value = $column->toBase64Col($column_name);
        $this->assertEquals('TO_BASE64('.$column_name.') AS '.$column_name, $value);
    }
    
    public function testFromBase64Val()
    {
        $column_name = 'test_field_name';
        $column = new _testColumns();
        $value = $column->fromBase64Val($column_name);
        $this->assertEquals('FROM_BASE64(\'' . base64_encode($column_name) . '\')', $value);
    }
    
    public function testGeomFromTextVal()
    {
        $column_name = 'test_field_name';
        $column = new _testColumns();
        $value = $column->geomFromTextVal($column_name);
        $this->assertEquals('GeomFromText(\''.$column_name.'\')', $value);
        
    }
}