<?php
/**
 * mithra62 - Unit Test
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/BackupPro/tests/Backup/DetailsTest.php
 */
 
namespace mithra62\BackupPro\tests\Backup;

use \mithra62\BackupPro\Backup\Details;
use \mithra62\tests\TestFixture;

class DetailsTest extends TestFixture
{
    
    public function getWorkingDir()
    {
        return realpath(dirname(__FILE__).'/../working_dir');
    }
    
    public function testDefaults()
    {
        $this->assertClassHasAttribute('details_directory', '\\mithra62\\BackupPro\\Backup\\Details');
        $this->assertClassHasAttribute('details_ext', '\\mithra62\\BackupPro\\Backup\\Details');
        $this->assertClassHasAttribute('details_prototype', '\\mithra62\\BackupPro\\Backup\\Details');
        
        $details = new Details;
        
        $this->assertInstanceOf('\\mithra62\\BackupPro\\Backup\\Details', $details);
        
        $this->assertObjectHasAttribute('details_directory', $details);
        $this->assertObjectHasAttribute('details_ext', $details);
        $this->assertObjectHasAttribute('details_prototype', $details);
        
        $this->assertEquals('.m62', $details->getDetailsExt());
        $this->assertEquals('.meta', $details->getDetailsDir());
    }
    
    public function testDefaultPrototype()
    {
        $details = new Details;
        $this->assertArrayHasKey('note', $details->getDetailsPrototype());
        $this->assertArrayHasKey('hash', $details->getDetailsPrototype());
        $this->assertArrayHasKey('storage', $details->getDetailsPrototype());
        $this->assertArrayHasKey('details', $details->getDetailsPrototype());
        $this->assertArrayHasKey('item_count', $details->getDetailsPrototype());
        $this->assertArrayHasKey('uncompressed_size', $details->getDetailsPrototype());
        $this->assertArrayHasKey('created_by', $details->getDetailsPrototype());
        $this->assertArrayHasKey('verified', $details->getDetailsPrototype());
        $this->assertArrayHasKey('verified_details', $details->getDetailsPrototype());
        $this->assertArrayHasKey('time_taken', $details->getDetailsPrototype());
        $this->assertArrayHasKey('max_memory', $details->getDetailsPrototype());
    }
    
    public function testCreateDetailsFile()
    {
        $path = $this->getWorkingDir();
        $details = new Details;
        
        //$details->createDetailsFile('test_details_file', $path, array());
    }
}