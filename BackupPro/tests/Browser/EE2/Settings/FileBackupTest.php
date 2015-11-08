<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		2.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/EE2/Settings/DbTest.php
 */

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\AbstractBase\Settings\FileBackup;
use mithra62\BackupPro\tests\Browser\EE2Trait;

/**
 * mithra62 - (Selenium) Db Settings object Unit Tests
 *
 * Executes all teh tests by platform using the below definitions
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
class FileBackupTest extends FileBackup
{
    use EE2Trait;

    /**
     * Disable this since we want full browser support
     */
    public function setUp()
    {
    
    }
    
    /**
     * Disable this since we want full browser support
     */
    public function teardown()
    {
    
    }
}