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

use mithra62\BackupPro\Console;
use mithra62\BackupPro\tests\TestFixture;

class ConsoleTest extends TestFixture
{
    public function testInstance()
    {
        $console = new Console;
        $this->assertInstanceOf('JaegerApp\Console', $console);
    }
    
    public function testGetArgsReturnInstance()
    {
        $console = new Console;
        $args = $console->getArgs(array(true));        
        $this->assertInstanceOf('cli\Arguments', $args);
    }
    
    public function testGetArgsFlags()
    {
        $console = new Console;
        $args = $console->getArgs(array(true));  
        $flags = $args->getFlags();
        $this->assertArrayHasKey('version', $flags);
        $this->assertArrayHasKey('help', $flags);
        $this->assertArrayHasKey('verbose', $flags);
    }
    
    public function testGetArgsOptions()
    {
        $console = new Console;
        $args = $console->getArgs(array(true));  
        $options = $args->getOptions();
        $this->assertArrayHasKey('backup', $options);
        $this->assertTrue(in_array('B', $options['backup']['aliases']));
        $this->assertEquals('database', $options['backup']['default']);
        $this->assertEquals('[file] [database] [integrity]', $options['backup']['description']);
        //$this->assertArrayHasKey('version', $flags);
        
    }
}