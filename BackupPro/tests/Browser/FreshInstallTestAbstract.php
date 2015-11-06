<?php

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

abstract class FreshInstallTestAbstract extends TestFixture  
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
    
    public function testDbBackupViewDefault()
    {
        $this->session->visit( $this->url('db_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No database backups exist yet.'));
        $this->assertTrue($this->session->getPage()->hasContent('Would you like to take a database backup now?'));
    }
    
    public function testFileBackupViewDefault()
    {
        $this->session->visit( $this->url('file_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No file backups exist yet.'));
        $this->assertTrue($this->session->getPage()->hasContent('Would you like to take a file backup now?'));
    }
    
    public function testBackupDbConfirmDefault()
    {
        $this->session->visit( $this->url('db_backup') );
        $this->assertTrue($this->session->getPage()->hasContent('You\'re going to need to fix the below configuration errors before you can start taking backups:'));
        $this->assertTrue($this->session->getPage()->hasContent('No Storage Locations have been setup yet!'));
        $this->assertTrue($this->session->getPage()->hasContent('Setup Storage Location'));
    }
    
    public function testBackupFilesConfirmDefault()
    {
        $this->session->visit( $this->url('file_backup') );
        $this->assertTrue($this->session->getPage()->hasContent('You\'re going to need to fix the below configuration errors before you can start taking backups:'));
        $this->assertTrue($this->session->getPage()->hasContent('No Storage Locations have been setup yet!'));
        $this->assertTrue($this->session->getPage()->hasContent('Setup Storage Location'));
        $this->assertTrue($this->session->getPage()->hasContent('No File Backup Locations have been configured.'));
        $this->assertTrue($this->session->getPage()->hasContent('Set File Backup Locations'));
    }

}