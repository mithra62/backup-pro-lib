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

use JaegerApp\Db;
use mithra62\BackupPro\Restore;
use mithra62\BackupPro\tests\TestFixture;

class RestoreTest extends TestFixture
{
    public function testInstance()
    {
        $restore = new Restore(new Db);
        $this->assertInstanceOf('mithra62\BackupPro\Backup', $restore);
    }
    
    public function testGetRestoreTimeException() 
    {
        $this->setExpectedException('mithra62\BackupPro\Exceptions\RestoreException');
        $restore = new Restore(new Db);
        $restore->getRestoreTime();
    }
    
    public function testGetRestoreTimeValue()
    {
        $restore = new Restore(new Db);
        $restore->startTimer();
        sleep(1);
        $restore_time = round($restore->getRestoreTime());
        $this->assertEquals('1', $restore_time);
    }
    
    public function testGetDatabaseInstance()
    {
        $restore = new Restore(new Db);
        $this->assertInstanceOf('mithra62\BackupPro\Restore\Database', $restore->getDatabase());
    }
    
    public function testGetBackupInfoDefaultValue()
    {
        $restore = new Restore(new Db);
        $this->assertTrue( is_array( $restore->getBackupInfo() ) );
        $this->assertCount(0, $restore->getBackupInfo());
    }
    
    public function testSetBackupInfoReturnValue()
    {
        $restore = new Restore(new Db);
        $this->assertInstanceOf('mithra62\BackupPro\Restore', $restore->setBackupInfo( array() ));
    }
    
    public function testSetBackupInfoAction()
    {
        $restore = new Restore(new Db);
        $expected = array('t');
        $restore->setBackupInfo($expected);
        $this->assertEquals($expected, $restore->getBackupInfo());
    }
}