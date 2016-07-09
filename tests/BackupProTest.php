<?php
/**
 * mithra62 - Backup Pro
 * 
 * Backup Unit Tests
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/BackupProTest.php
 */
namespace mithra62\BackupPro\tests;

use mithra62\BackupPro\BackupPro;
use TestFixture;

class _bp_interface_test implements BackupPro
{
    
}

class BackupProTest extends TestFixture
{
    public function testVersionConstantExists()
    {
        $class = new _bp_interface_test;
        $this->assertNotFalse($class::version);
    }
    
    public function testVersionConstantFormat()
    {
        $class = new _bp_interface_test;
        $parts = explode('.', $class::version);
        $this->assertLessThanOrEqual(3, count($parts));
        foreach($parts AS $part)
        {
            $this->assertTrue(is_numeric($part));
        }
    }   
    
    public function testBuildConstantExists()
    {
        $class = new _bp_interface_test;
        $this->assertNotFalse($class::build);
        $this->assertTrue(is_numeric($class::build));
    }
    
    public function testNameConstant()
    {
        $class = new _bp_interface_test;
        $this->assertEquals('backup_pro', $class::name);
    }
    
    public function testLangFileConstant()
    {
        $class = new _bp_interface_test;
        $this->assertEquals('backup_pro', $class::lang_file);
    }
    
    public function testDescConstant()
    {
        $class = new _bp_interface_test;
        $this->assertEquals('backup_pro_desc', $class::desc);
    }
    
    public function testDocsUrlConstant()
    {
        $class = new _bp_interface_test;
        $this->assertEquals('https://mithra62.com/docs/table-of-contents/backup-pro', $class::docs_url);
    }
    
    public function testBaseUrlConstant()
    {
        $class = new _bp_interface_test;
        $this->assertEquals('https://mithra62.com/', $class::base_url);
    }
}