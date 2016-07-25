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

use mithra62\BackupPro\Bootstrap;
use mithra62\BackupPro\Platforms\Console;
use mithra62\BackupPro\tests\TestFixture;

class BootstrapTest extends TestFixture
{
    public function testInstance()
    {
        $bootstrap = new Bootstrap;
        $this->assertInstanceOf('JaegerApp\Bootstrap', $bootstrap);
    }
    
    public function testGetServicesReturnInstance()
    {
        $bootstrap = new Bootstrap;
        $this->assertInstanceOf('Pimple\Container', $bootstrap->getServices());
    }
    
    public function testSettingsServiceInstance()
    {
        $bootstrap = new Bootstrap;
        $this->platform = new Console();
        $bootstrap->setService('platform', function ($c) {
            return $this->platform;
        });        
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('settings', $services);
        $this->assertInstanceOf('JaegerApp\Settings', $services['settings']);
    }
    
    public function testBackupInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('backup', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Backup', $services['backup']);
    }
    
    public function testBackupsInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('backups', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Backups', $services['backups']);
    }
    
    public function testRestoreInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('restore', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Restore', $services['restore']);
    }
    
    public function testErrorsInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('errors', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Errors', $services['errors']);
    }
    
    public function testSettingsValidateInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('settings_validate', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Validate', $services['settings_validate']);
    }
    
    public function testConsoleInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('console', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Console', $services['console']);
    }
    
    public function testRestInstance()
    {
        $bootstrap = new Bootstrap;
        $services = $bootstrap->getServices();
        $this->assertArrayHasKey('rest', $services);
        $this->assertInstanceOf('mithra62\BackupPro\Rest', $services['rest']);
    }
}