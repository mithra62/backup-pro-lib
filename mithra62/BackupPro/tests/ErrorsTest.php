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

use mithra62\BackupPro\Errors;
use mithra62\tests\TestFixture;

class ErrorsTest extends TestFixture
{
    public function testCheckStorageLocationsReturnInstance()
    {
        $errors = new Errors;
        $this->assertInstanceOf('mithra62\BackupPro\Errors', $errors->checkStorageLocations(array()));
    }
    
    public function testCheckStorageLocationsNoValues()
    {
        $errors = new Errors;
        $errors->checkStorageLocations(array());
        $this->assertEquals(1, $errors->totalErrors());
    }
    
    public function testCheckStorageLocationsErrorKey()
    {
        $errors = new Errors;
        $errors->checkStorageLocations(array());
        $check = $errors->getErrors();
        $this->assertArrayHasKey('no_storage_locations_setup', $check);
    }
    
    public function testCheckFileBackupLocationsReturnInstance()
    {
        $errors = new Errors;
        $this->assertInstanceOf('mithra62\BackupPro\Errors', $errors->checkStorageLocations(array()));
    }
    
    public function testCheckFileBackupLocationsNoValues()
    {
        $errors = new Errors;
        $errors->checkFileBackupLocations(array());
        $this->assertEquals(1, $errors->totalErrors());
    }
    
    public function testCheckFileBackupLocationsErrorKey()
    {
        $errors = new Errors;
        $errors->checkFileBackupLocations(array());
        $check = $errors->getErrors();
        $this->assertArrayHasKey('no_backup_file_location', $check);
    }
    
    public function testCheckWorkingDirectorysReturnInstance()
    {
        $errors = new Errors;
        $this->assertInstanceOf('mithra62\BackupPro\Errors', $errors->checkWorkingDirectory(''));
    }
    
    public function testCheckWorkingDirectoryNoValues()
    {
        $errors = new Errors;
        $errors->checkWorkingDirectory('');
        $this->assertEquals(1, $errors->totalErrors());
    }
    
    public function testCheckWorkingDirectoryErrorKey()
    {
        $errors = new Errors;
        $errors->checkWorkingDirectory('');
        $check = $errors->getErrors();
        $this->assertArrayHasKey('invalid_working_directory', $check);
    }
    
    public function testCheckBackupStateReturnInstance()
    {
        $errors = new Errors;
        $this->assertInstanceOf('mithra62\BackupPro\Errors', $errors->checkBackupState(
            new \mithra62\BackupPro\Backups(new \mithra62\Files ), 
            array('working_directory' => '', 'storage_details' => array(), 'file_backup_alert_threshold' => '', 'db_backup_alert_threshold' => ''))
        );
    }
    
    public function testCheckBackupStateInvalidArgumentException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $errors = new Errors;
        $this->assertInstanceOf('mithra62\BackupPro\Errors', $errors->checkBackupState(
            new \mithra62\BackupPro\Backups(new \mithra62\Files ),
            array())
        );
    }
    
}