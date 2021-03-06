<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/Prestashop/Storage/FtpEngine.php
 */
namespace mithra62\BackupPro\tests\Browser\Prestashop\Storage;

use mithra62\BackupPro\tests\Browser\AbstractBase\Storage\FtpEngine;
use mithra62\BackupPro\tests\Browser\PrestashopTrait;

/**
 * mithra62 - (Selenium) Storage FTP Engine object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class FtpEngineTest extends FtpEngine
{
    use PrestashopTrait;

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