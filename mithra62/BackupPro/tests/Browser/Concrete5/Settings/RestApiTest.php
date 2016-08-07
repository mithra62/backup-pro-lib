<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/EE2/Settings/RestApiTest.php
 */
namespace mithra62\BackupPro\tests\Browser\Concrete5;

use mithra62\BackupPro\tests\Browser\AbstractBase\Settings\RestApi;
use mithra62\BackupPro\tests\Browser\C5Trait;

/**
 * mithra62 - (Selenium) Integrity Agent Settings object Unit Tests
 *
 * Executes all teh tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class RestApiTest extends RestApi
{
    use C5Trait;

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