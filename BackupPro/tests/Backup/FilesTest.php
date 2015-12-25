<?php
/**
 * mithra62 - Unit Test
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/BackupPro/tests/Backup/FilesTest.php
 */
namespace mithra62\BackupPro\tests\Backup;

use \mithra62\Db;
use \mithra62\BackupPro\Backup;
use \mithra62\BackupPro\Backup\Files;
use \mithra62\tests\TestFixture;

class FilesTest extends TestFixture
{
    protected function getBackupObj()
    {
        $this->backup = new Backup(new Db());
        $this->backup->setServices(new \Pimple\Container()); // should mock this up :|
        return $this->backup;
    }
    
    public function testInstance()
    {
        $files = new Files();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\AbstractBackup', $files);
    }
    
    public function testExcludePathsPropertyDefault()
    {
        $files = new Files();
        $this->assertClassHasAttribute('exclude_paths', '\\mithra62\\BackupPro\\Backup\\Files');
        $this->assertObjectHasAttribute('exclude_paths', $files);
        $this->assertCount(1, $files->setBackup( $this->getBackupObj() )->getExcludePaths());
    }
    
    public function testExcludeRegexPropertyDefault()
    {
        $files = new Files();
        $this->assertClassHasAttribute('exclude_regex', '\\mithra62\\BackupPro\\Backup\\Files');
        $this->assertObjectHasAttribute('exclude_regex', $files);
        $this->assertTrue($files->getExludeRegex());
    }
    
    public function testBackupPathsProprtyDefault()
    {
        $files = new Files();
        $this->assertClassHasAttribute('backup_paths', '\\mithra62\\BackupPro\\Backup\\Files');
        $this->assertObjectHasAttribute('backup_paths', $files);
        $this->assertTrue(is_array($files->getBackupPaths()));
        $this->assertCount(0, $files->getBackupPaths());
    }
    
    public function testTotalFilesPropertyDefault()
    {
        $files = new Files();
        $this->assertClassHasAttribute('total_files', '\\mithra62\\BackupPro\\Backup\\Files');
        $this->assertObjectHasAttribute('total_files', $files);
        $this->assertEquals(0, $files->getTotalFiles());
    }
    
    public function testTotalUncompressedFileSizePropertyDefault()
    {
        $files = new Files();
        $this->assertClassHasAttribute('total_uncompressed_filesize', '\\mithra62\\BackupPro\\Backup\\Files');
        $this->assertObjectHasAttribute('total_uncompressed_filesize', $files);
        $this->assertEquals(0, $files->getTotalFileSize());
    }
    
    public function testFilePropertyDefault()
    {
        $files = new Files();
        $this->assertClassHasAttribute('file', '\\mithra62\\BackupPro\\Backup\\Files');
        $this->assertObjectHasAttribute('file', $files);
    }
    
    public function testSetTotalFilesMethodSimple()
    {
        $files = new Files();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\AbstractBackup', $files->setTotalFiles(500));
        $this->assertEquals(1000, $files->setTotalFiles(500)->getTotalFiles());
    }
    
    public function testSetTotalFilesMethodRegresh()
    {
        $files = new Files();
        $files->setTotalFiles(500)->setTotalFiles(600);
        $this->assertEquals(11, $files->setTotalFiles(11, true)->getTotalFiles());
    }
}