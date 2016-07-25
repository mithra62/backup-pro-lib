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
namespace mithra62\BackupPro\tests;

use mithra62\BackupPro\Rest;
use mithra62\BackupPro\Platforms\Console;
use mithra62\BackupPro\tests\TestFixture;

class RestTest extends TestFixture
{
    public function testInstance()
    {
        $rest = new Rest;
        $this->assertInstanceOf('JaegerApp\Rest', $rest);
    }
    
    public function testGetServerInstance()
    {
        $rest = new Rest;
        $rest->setPlatform( new Console); //just to stub it
        $this->assertInstanceOf('mithra62\BackupPro\Rest\Server', $rest->getServer());
    }
}