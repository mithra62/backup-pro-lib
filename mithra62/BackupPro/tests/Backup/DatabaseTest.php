<?php
/**
 * mithra62 - Unit Test
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/BackupPro/tests/Backup/DatabaseTest.php
 */
namespace mithra62\BackupPro\tests\Backup;

use \mithra62\Db;
use \mithra62\BackupPro\Backup;
use \mithra62\BackupPro\Backup\Database;
use \mithra62\tests\TestFixture;

class DatabaseTest extends TestFixture
{
    protected function getBackupObj()
    {
        $this->backup = new Backup(new Db());
        $this->backup->setServices(new \Pimple\Container()); // should mock this up :|
        return $this->backup;
    }
    
    public function testInstance()
    {
        $database = new Database();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\AbstractBackup', $database);
    }
    
    public function testEnginePropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('engine', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('engine', $database);
    }
    
    public function testIgnoreTablesPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('ignore_tables', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('ignore_tables', $database);
        $this->assertCount(0, $database->getIgnoreTables());
    }
    
    public function testIgnoreTablesDataPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('ignore_table_data', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('ignore_table_data', $database);
        $this->assertCount(0, $database->getIgnoreTableData());
    }
    
    public function testArchivePreSqlPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('archive_pre_sql', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('archive_pre_sql', $database);
        $this->assertNull($database->getArchivePreSql());
    }
    
    public function testArchivePostSqlPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('archive_post_sql', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('archive_post_sql', $database);
        $this->assertNull($database->getArchivePostSql());
    }
    
    public function testExecutePreSqlPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('execute_pre_sql', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('execute_pre_sql', $database);
        $this->assertNull($database->getExecutePreSql());
    }
    
    public function testExecutePostSqlPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('execute_post_sql', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('execute_post_sql', $database);
        $this->assertNull($database->getExecutePostSql());
    }
    
    public function testOutputPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('output', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('output', $database);
    }
    
    public function testOutputPathNamePropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('output_path_name', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('output_path_name', $database);
        $this->assertNull($database->getOutputName());
    }
    
    public function testEngineCmdPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('engine_cmd', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('engine_cmd', $database);
        $this->assertNull($database->getEngineCmd());
    }
    
    public function testShellPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('shell', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('shell', $database);
        $this->assertNull($database->getShell());
    }
    
    public function testTablesPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('tables', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('tables', $database);
        $this->assertCount(0, $database->getTables());
    }
    
    public function testSqlGroupByPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('sql_group_by', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('sql_group_by', $database);
        $this->assertEquals(250, $database->getSqlGroupBy());
    }
    
    public function testTableSqlGroupByPropertyDefault()
    {
        $database = new Database();
        $this->assertClassHasAttribute('table_sql_group_by', '\\mithra62\\BackupPro\\Backup\\Database');
        $this->assertObjectHasAttribute('table_sql_group_by', $database);
        $this->assertCount(0, $database->getTableSqlGroupBy());
    }
    
    public function testSetOutputNameReturnInstance()
    {
        $database = new Database();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database', $database->setOutputName('test'));
    }
    
    public function testSetOutputNameSetValue()
    {
        $database = new Database();
        $this->assertEquals('test', $database->setOutputName('test')->getOutputName());
    }
    
    public function testSetEngineBadValue()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $database = new Database();
        $database->setEngine('fdsa');
    }
    
    public function testSetEngineReturnInstance()
    {
        $database = new Database();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database', $database->setEngine('php'));
    }
    
    public function testSetEngineGoodValue()
    {
        $database = new Database();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database\Engines\Php', $database->setEngine('php')->getEngine());
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database\DbAbstract', $database->setEngine('php')->getEngine());
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database\DbInterface', $database->setEngine('php')->getEngine());
    }
    
    public function testGetEngineDefaultValue()
    {
        $database = new Database();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database\Engines\Php', $database->getEngine());
    }
    
    public function testGetAvailableEnginesOptions()
    {
        $database = new Database();
        $this->assertCount(2, $database->getAvailableEnginesOptions());
    }
    
    public function testSetIgnoreTablesEmptyArray()
    {
        $database = new Database();
        $tables = array();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database', $database->setIgnoreTables($tables));
    }
    
    public function testSetIgnoreTablesEmptyValue()
    {
        $database = new Database();
        $tables = array();
        $this->count(0, $database->setIgnoreTables($tables)->getIgnoreTables());
    }
    
    public function testSetIgnoreTablesGoodTableValue()
    {
        $database = new Database();
        $tables = array('test', 'test2');
        $this->count(2, $database->setIgnoreTableData($tables)->getIgnoreTables());
    }
    
    public function testSetIgnoreTablesDataEmptyArray()
    {
        $database = new Database();
        $tables = array();
        $this->assertInstanceOf('mithra62\BackupPro\Backup\Database', $database->setIgnoreTableData($tables));
    }
    
    public function testSetIgnoreTablesDataEmptyValue()
    {
        $database = new Database();
        $tables = array();
        $this->count(0, $database->setIgnoreTableData($tables)->getIgnoreTableData());
    }
    
    public function testSetIgnoreTablesGoodDataTableValue()
    {
        $database = new Database();
        $tables = array('test', 'test2');
        $this->count(2, $database->setIgnoreTableData($tables)->getIgnoreTableData());
    }
    
    public function testRemoveWhiteSpace()
    {
        $database = new Database();
        $string = "test\nagain\tanother";
        $this->assertEquals("test again\tanother", $database->removeWhiteSpace($string));
    }
}