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
use mithra62\BackupPro\tests\TestFixture;

class DetailsTest extends TestFixture
{

    public function getWorkingDir()
    {
        return realpath(dirname(__FILE__) . '/../working_dir');
    }
    
    public function testDetailsDirectoryPropertyDefault()
    {
        $details = new Details();
        $this->assertClassHasAttribute('details_directory', '\\mithra62\\BackupPro\\Backup\\Details');
        $this->assertObjectHasAttribute('details_directory', $details);
        $this->assertEquals('.meta', $details->getDetailsDir());
    }
    
    public function testDetailsExtPropertyDefault()
    {
        $details = new Details();
        $this->assertClassHasAttribute('details_ext', '\\mithra62\\BackupPro\\Backup\\Details');
        $this->assertObjectHasAttribute('details_ext', $details);
        $this->assertEquals('.m62', $details->getDetailsExt());
    }
    
    public function testDetailsPrototypPropetyDefault()
    {
        $details = new Details();
        $this->assertClassHasAttribute('details_prototype', '\\mithra62\\BackupPro\\Backup\\Details');
        $this->assertObjectHasAttribute('details_prototype', $details);
    }

    public function testDefaultPrototype()
    {
        $details = new Details();
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
    
    public function testGetDetailsPath()
    {
        $details = new Details();
        $path = $details->getDetailsPath($this->getWorkingDir());
        $this->assertEquals('.meta', substr($path, -5)); 
    }
    
    public function testSetDetailsExt()
    {
        $value = '.ttt';
        $details = new Details();
        $details->setDetailsExt($value);
        $this->assertEquals($value, $details->getDetailsExt());
    }
}