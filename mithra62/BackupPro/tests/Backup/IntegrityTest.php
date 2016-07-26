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
namespace mithra62\BackupPro\tests\Backup;

use mithra62\BackupPro\Backup\Integrity;
use mithra62\BackupPro\tests\TestFixture;


class IntegrityTest extends TestFixture
{
    public function testTraitExists()
    {
        $integrity = new Integrity();
        $this->assertTrue( in_array('JaegerApp\Traits\DateTime', class_uses($integrity))) ;
    }
    
    public function testStoragePropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('storage', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('storage', $integrity);
        $this->assertNull($integrity->getStorage());
    }
    
    public function testCompressPropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('compress', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('compress', $integrity);
        $this->assertInstanceOf('JaegerApp\Compress', $integrity->getCompress());
    }
    
    public function testRestorePropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('restore', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('restore', $integrity);
        $this->assertNull($integrity->getRestore());
    }
    
    public function testShellPropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('shell', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('shell', $integrity);
        $this->assertNull($integrity->getShell());
    }
    
    public function testTestDbNamePropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('test_db_name', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('test_db_name', $integrity);
        $this->assertFalse($integrity->getTestDbName());
    }
    
    public function testBackupInfoPropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('backup_info', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('backup_info', $integrity);
        $this->assertTrue( is_array($integrity->getBackupInfo() ));
        $this->assertCount(0, $integrity->getBackupInfo() );
    }
    
    public function testSettingsPropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('settings', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('settings', $integrity);
        $this->assertTrue( is_array($integrity->getSettings() ));
        $this->assertCount(0, $integrity->getSettings() );
    }
    
    public function testFilePropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('file', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('file', $integrity);
        $this->assertNull($integrity->getFile());
    }
    
    public function testDbConfPropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('db_conf', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('db_conf', $integrity);
        $this->assertTrue( is_array($integrity->getDbConf() ));
        $this->assertCount(0, $integrity->getDbConf() );
    }
    
    public function testServicesPropertyDefault()
    {
        $integrity = new Integrity;
        $this->assertClassHasAttribute('services', '\\mithra62\\BackupPro\\Backup\\Integrity');
        $this->assertObjectHasAttribute('services', $integrity);
        $this->assertTrue( is_array($integrity->getServices() ));
        $this->assertCount(0, $integrity->getServices() );
    }
    
    public function testSetDbConfMethod()
    {
        $integrity = new Integrity;
        $services = array('test', 'test2');
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Integrity', $integrity->setDbConf($services));
        $this->assertCount(2, $integrity->getDbConf() );
    }
    
    public function testSetSettingsMethod()
    {
        $integrity = new Integrity;
        $settings = array('test', 'test2');
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Integrity', $integrity->setSettings($settings));
        $this->assertCount(2, $integrity->getSettings() );
    }
    
    public function testSetBackupInfo()
    {
        $integrity = new Integrity;
        $settings = array('test', 'test2');
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Integrity', $integrity->setBackupInfo($settings));
        $this->assertCount(2, $integrity->getBackupInfo() );
    }
    
    public function testSetTestDbNameStringValue()
    {
        $this->setExpectedException('mithra62\BackupPro\Exceptions\Backup\IntegrityException');
        $integrity = new Integrity;
        $settings = array('test', 'test2');
        $integrity->setTestDbName($settings);
    }
    
    public function testSetTestDbNameGoodValue()
    {
        $db_name = 'fdsafdsa';
        $integrity = new Integrity;
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Integrity', $integrity->setTestDbName($db_name));
        $this->assertEquals($db_name, $integrity->getTestDbName() );        
    }
}