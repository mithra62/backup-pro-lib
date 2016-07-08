<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/WP/FreshInstallTest.php
 */
namespace mithra62\BackupPro\tests\Browser\WP;

use mithra62\BackupPro\tests\Browser\AbstractBase\FreshInstall;
use mithra62\BackupPro\tests\Browser\WPTrait;

/**
 * mithra62 - (Selenium) Fresh Install Browser Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class FreshInstallTest extends FreshInstall
{
    use WPTrait;

    /**
     * Disable this since we want full browser support
     */
    public function setUp()
    {}

    /**
     * Disable this since we want full browser support
     */
    public function teardown()
    {}
}