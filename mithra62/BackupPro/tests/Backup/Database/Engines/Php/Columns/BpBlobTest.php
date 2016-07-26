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

use mithra62\BackupPro\Backup\Database\Engines\Php\Columns\BpBlob;
use mithra62\BackupPro\tests\TestFixture;


class BpBlobTest extends TestFixture
{
    public function testBpBlobInstance()
    {
        $column = new BpBlob;
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database\Engines\Php\Columns', $column);
    }
    
    public function testGetFieldNameReturnValue()
    {
        $column = new BpBlob;
        $value = array('Field' => 'test_name');
        $this->assertEquals('`test_name`', $column->getFieldName($value));
    }    
}