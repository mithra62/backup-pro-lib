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
    public function testDefaults()
    {
        $this->assertClassHasAttribute('storage_path', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('db', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('storage', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('progress', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('database', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('compress', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('details', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('db_info', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('timer_start', '\\mithra62\\BackupPro\Backup');
        $this->assertClassHasAttribute('timer_stop', '\\mithra62\\BackupPro\Backup');
        
        $backup = new Backup( new Db );
        $backup->setServices( new \Pimple\Container() ); //should mock this up :|
        
        $this->assertObjectHasAttribute('storage_path', $backup);
        $this->assertObjectHasAttribute('db', $backup);
        $this->assertObjectHasAttribute('storage', $backup);
        $this->assertObjectHasAttribute('progress', $backup);
        $this->assertObjectHasAttribute('database', $backup);
        $this->assertObjectHasAttribute('compress', $backup);
        $this->assertObjectHasAttribute('details', $backup);
        $this->assertObjectHasAttribute('db_info', $backup);
        $this->assertObjectHasAttribute('timer_start', $backup);
        $this->assertObjectHasAttribute('timer_stop', $backup);
        
        $this->assertNull($backup->getStoragePath());
        $this->assertCount(0, $backup->getDbInfo());
        
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Progress', $backup->getProgress());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Database', $backup->getDatabase());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Storage', $backup->getStorage());
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Details', $backup->getDetails());
        $this->assertInstanceOf('\\mithra62\\Compress', $backup->getCompress());
        $this->assertInstanceOf('\\mithra62\\Db', $backup->getDb());
    }
    
    public function testStoragePath()
    {
        $backup = new Backup( new Db );
    }
}