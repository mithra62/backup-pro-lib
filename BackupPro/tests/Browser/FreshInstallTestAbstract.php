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
    }
    
    /**
     * @depends testDashboardDefault
     */
    public function testDbBackupViewDefault()
    {
        $this->session->visit( $this->url('db_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No database backups exist yet.'));
    }
    
    /**
     * @depends testDbBackupViewDefault
     */
    public function testFileBackupViewDefault()
    {
        $this->session->visit( $this->url('file_backups') );
        $this->assertTrue($this->session->getPage()->hasContent('No file backups exist yet.'));
    }

}