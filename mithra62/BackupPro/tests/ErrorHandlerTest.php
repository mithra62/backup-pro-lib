<?php
/**
 * mithra62 - Backup Pro
 * 
 * Backup Unit Tests
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/BackupTest.php
 */
namespace mithra62\BackupPro\tests;

use mithra62\BackupPro\ErrorHandler;
use mithra62\BackupPro\tests\TestFixture;

class ErrorHandlerTest extends TestFixture
{   
    public function tearDown()
    {
        $eh = new ErrorHandler;
        $eh->unregister();
    }
    
    public function testSetDebugMode()
    {
        $eh = new ErrorHandler;
        $eh->setDebugMode(false);
        $this->assertFalse($eh->getDebugMode());
        $eh->setDebugMode(true);
        $this->assertTrue($eh->getDebugMode());
        $eh->setDebugMode(false);
        $this->assertFalse($eh->getDebugMode());
    }
    
    public function testDebugModeDefaultValue()
    {
        $eh = new ErrorHandler;
        $this->assertFalse($eh->getDebugMode());
    }
    
    public function testSetDebugModeReturnInstance()
    {
        $eh = new ErrorHandler;
        $this->assertInstanceOf('mithra62\BackupPro\ErrorHandler', $eh->setDebugMode(true));
    }
    
    public function testRegisterReturnInstance()
    {
        $eh = new ErrorHandler;
        $this->assertInstanceOf('mithra62\BackupPro\ErrorHandler', $eh->register());
    }
}