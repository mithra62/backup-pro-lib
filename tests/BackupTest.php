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

use mithra62\BackupPro\Backup;
use mithra62\Db;
use mithra62\tests\TestFixture;

class BackupTest extends TestFixture
{
    protected $backup = null;
    
    protected function getBackupObj()
    {
        $this->backup = new Backup(new Db());
        $this->backup->setServices(new \Pimple\Container()); // should mock this up :|
        return $this->backup;
    }
    
    public function testStoragePathPropertyDefaults()
    {
        $backup = $this->getBackupObj();
        $this->assertClassHasAttribute('storage_path', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('storage_path', $backup);
        $this->assertNull($backup->getStoragePath());
    }
    
    public function testDbPropertyDefaults()
    {
        $backup = $this->getBackupObj();
        $this->assertClassHasAttribute('db', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('db', $backup);
        $this->assertInstanceOf('\\mithra62\\Db', $backup->getDb());
    }
    
    public function testStoragePropertyDefaults()
    {
        $this->assertClassHasAttribute('storage', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('storage', $this->getBackupObj());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Storage', $this->getBackupObj()->getStorage());
    }
    
    public function testProgressPropertyDefaults()
    {
        $this->assertClassHasAttribute('progress', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('progress', $this->getBackupObj());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Progress', $this->getBackupObj()->getProgress());
    }
    
    public function testDatabasePropertyDefaults()
    {
        $this->assertClassHasAttribute('database', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('database', $this->getBackupObj());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Database', $this->getBackupObj()->getDatabase());
    }
    
    public function testFilePropertyDefaults()
    {
        $this->assertClassHasAttribute('file', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('file', $this->getBackupObj());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Files', $this->getBackupObj()->getFile());
    }
    
    public function testCompressPropertyDefaults()
    {
        $this->assertClassHasAttribute('compress', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('compress', $this->getBackupObj());
        $this->assertInstanceOf('\\mithra62\\Compress', $this->getBackupObj()->getCompress());
    }
    
    public function testDetailsPropertyDefaults()
    {
        $this->assertClassHasAttribute('details', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('details', $this->getBackupObj());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Details', $this->getBackupObj()->getDetails());
    }
    
    public function testDbInfoPropertyDefaults()
    {
        $this->assertClassHasAttribute('db_info', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('db_info', $this->getBackupObj());
        $this->assertTrue(is_array($this->getBackupObj()->getDbInfo()));
        $this->assertCount(0, $this->getBackupObj()->getDbInfo());
    }
    
    public function testTimerStartPropertyDefaults()
    {
        $this->assertClassHasAttribute('timer_start', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('timer_start', $this->getBackupObj());
    }
    
    public function testTimerStopPropertyDefaults()
    {
        $this->assertClassHasAttribute('timer_stop', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('timer_stop', $this->getBackupObj());
    }
    
    public function testServicesPropertyDefaults()
    {
        $this->assertClassHasAttribute('services', '\\mithra62\\BackupPro\Backup');
        $this->assertObjectHasAttribute('services', $this->getBackupObj());
        $this->assertInstanceOf('\Pimple\Container', $this->getBackupObj()->getServices());
    }
    
    public function testStartTimerMethod()
    {
        $backup = $this->getBackupObj();
        $backup->startTimer();
        sleep(1);
        $backup_time = round($backup->getBackupTime());
        $this->assertEquals('1', $backup_time);
    }
    
    public function testSetDbInfoGoodStructure()
    {
        $backup = $this->getBackupObj();
        $db_info = array('user' => '', 'password' => '', 'database' => '', 'host' => '', 'prefix' => '');
        $this->assertInstanceOf('mithra62\BackupPro\Backup', $backup->setDbInfo($db_info));
        $this->assertEquals($db_info, $backup->getDbInfo());
    }
    
    public function testSetDbInfoBadStructure()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $backup = $this->getBackupObj();
        $db_info = array('username' => '', 'password' => '', 'database' => '', 'host' => '', 'prefix' => '');
        $backup->setDbInfo($db_info);
    }
}