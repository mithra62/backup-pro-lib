<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/tests/Browser/AbstractBase/Storage/NoEngines.php
 */
namespace mithra62\BackupPro\tests\Browser\AbstractBase\Storage;

use mithra62\BackupPro\tests\Browser\TestFixture;

/**
 * mithra62 - (Selenium) Storage No Engines object Unit Tests
 *
 * Executes all the tests by platform using the below definitions
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
abstract class NoEngines extends TestFixture
{

    /**
     * An instance of the mink selenium object
     * 
     * @var unknown
     */
    public $session = null;

    public function testNoStorageLocationsCreatedYet()
    {
        $this->login();
        sleep(2);
        $this->install_addon();
        
        $this->session->visit($this->url('storage_view_storage'));
        
        $page = $this->session->getPage();
        
        $page = $this->session->getPage();
        $this->assertTrue($this->session->getPage()
            ->hasContent('No Storage Locations Created Yet'));
        $this->uninstall_addon();
    }
}