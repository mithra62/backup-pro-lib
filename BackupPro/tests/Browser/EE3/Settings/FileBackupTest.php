<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/EE3/Settings/DbTest.php
 */

namespace mithra62\BackupPro\tests\Browser\EE3;

use mithra62\BackupPro\tests\Browser\AbstractBase\Settings\FileBackup;
use mithra62\BackupPro\tests\Browser\EE3Trait;

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
    use EE3Trait;

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