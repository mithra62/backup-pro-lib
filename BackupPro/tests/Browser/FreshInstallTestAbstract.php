<?php

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

class FreshInstallTestAbstract extends TestFixture  
{   
    public $session = null;
    
    public function testDashboardDefault()
    {
        $this->session->visit( $this->url('dashboard') );
        $this->assertTrue($this->session->getPage()->hasContent('No backups exist yet.'));
        $this->assertTrue($this->session->getPage()->hasContent('No Storage Locations have been setup yet!'));
        $this->assertTrue($this->session->getPage()->hasContent('Please enter your license number.'));
        $this->assertTrue($this->session->getPage()->hasContent('Would you like to take a database backup now?'));
    }
    
    /**
     * @depends testDashboardDefault
     */
    public function testDbBackupViewDefault()
    {
        $this->session->visit( $this->url('db_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No database backups exist yet.'));
        $this->assertTrue($this->session->getPage()->hasContent('Would you like to take a database backup now?'));
    }
    
    /**
     * @depends testDbBackupViewDefault
     */
    public function testFileBackupViewDefault()
    {
        $this->session->visit( $this->url('file_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No file backups exist yet.'));
        $this->assertTrue($this->session->getPage()->hasContent('Would you like to take a file backup now?'));
    }
    
    /**
     * @depends testDbBackupViewDefault
     */
    public function testBackupDbViewDefault()
    {
        $this->session->visit( $this->url('file_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No file backups exist yet.'));
        $this->assertTrue($this->session->getPage()->hasContent('Would you like to take a file backup now?'));
    }

}