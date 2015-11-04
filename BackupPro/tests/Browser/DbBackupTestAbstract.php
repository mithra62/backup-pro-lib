<?php

namespace mithra62\BackupPro\tests\Browser;

use mithra62\BackupPro\tests\Browser\TestFixture;

class DbBackupTestAbstract extends TestFixture  
{   
    public function testDashboardDefault()
    {
        $session = $this->login();
        $session->visit( $this->url('dashboard') );
        $this->assertTrue($session->getPage()->hasContent('No backups exist yet.'));
    }
    
    /**
     * @depends testDashboardDefault
     */
    public function testDbBackupViewDefault()
    {
        $session = $this->login();
        $session->visit( $this->url('db_backups') );
        $this->assertTrue($session->getPage()->hasContent('No database backups exist yet'));
    }
    
    /**
     * @depends testDbBackupViewDefault
     */
    public function testFileBackupViewDefault()
    {
        $session = $this->login();
        $session->visit( $this->url('file_backups') );
        $this->assertTrue($session->getPage()->hasContent('No file backups exist yet.'));
    }

}