<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/EE3/Storage/RcfEngine.php
 */
namespace mithra62\BackupPro\tests\Browser\EE3\Storage;

use mithra62\BackupPro\tests\Browser\AbstractBase\Storage\RcfEngine;
use mithra62\BackupPro\tests\Browser\EE3Trait;

/**
 * mithra62 - (Selenium) Rackspace Cloud Files Storage object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class RcfEngineTest extends RcfEngine
{
    use EE3Trait;

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