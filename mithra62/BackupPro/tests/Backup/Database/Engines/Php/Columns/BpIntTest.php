<?php
/**
 * mithra62 - Backup Pro
 * 
 * Console Unit Tests
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/BpBooleanTest.php
 */
namespace mithra62\BackupPro\tests\Backup\Database\Engines\Php;

use mithra62\BackupPro\Backup\Database\Engines\Php\Columns\BpInt;
use mithra62\BackupPro\tests\TestFixture;


class BpIntTest extends TestFixture
{
    public function testInstance()
    {
        $column = new BpInt;
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database\Engines\Php\Columns', $column);
    }  
}