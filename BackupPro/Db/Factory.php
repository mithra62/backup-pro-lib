<?php
namespace mithra62\BackupPro\Db;

class Factory
{
    public function __construct($dblogin, $dbpass, $dbname, $dbhost = null)
    {
        return new MySQLDB($dblogin, $dbpass, $dbname, $dbhost) ;
    }

}