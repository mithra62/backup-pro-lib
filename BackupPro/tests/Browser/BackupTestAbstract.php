<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/BackupTestAbstract.php
 */
 
namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - Backup Test Abastract
 *
 * Abstract method to execute Backup testing
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
abstract class BackupTestAbstract extends TestFixture  
{   
    public $session = null;
    
    public function testTakeDatabaseBackup()
    {
        
    }
}