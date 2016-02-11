<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/NoEnginesTest.php
 */
namespace mithra62\BackupPro\tests\Browser\Concrete5\Storage;

use mithra62\BackupPro\tests\Browser\AbstractBase\Storage\NoEngines;
use mithra62\BackupPro\tests\Browser\C5Trait;

/**
 * mithra62 - (Selenium) Storage Engines object Unit Tests
 *
 * Executes all teh tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class NoEnginesTest extends NoEngines
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