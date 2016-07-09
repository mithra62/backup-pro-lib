<?php
namespace mithra62\BackupPro\tests;

trait TestTrait
{

    /**
     * The name of the test database table
     * 
     * @var string
     */
    protected $test_table_name = 'm62_test_table';

    /**
     * The full path to the working directory any file system activity happens
     * 
     * @return string
     */
    protected function getWorkingDir()
    {
        return $this->dataPath().DIRECTORY_SEPARATOR.'working_dir';
    }

    /**
     * The full path to the data directory
     * 
     * @return string
     */
    protected function dataPath()
    {
        return realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR.'data');
    }

    /**
     * Just returns the path to the testing language1 directory
     * 
     * @return string
     */
    protected function lang1Path()
    {
        return realpath(dirname(__FILE__) . '/data/languages/language1');
    }

    /**
     * Just returns the path to the testing language2 directory
     * 
     * @return string
     */
    protected function lang2Path()
    {
        return realpath(dirname(__FILE__) . '/data/languages/language2');
    }

    /**
     * Just returns the path to the testing language3 directory
     * 
     * @return string
     */
    protected function lang3Path()
    {
        return realpath(dirname(__FILE__) . '/data/languages/language3');
    }

    /**
     * The Amazon S3 Test Credentials
     * 
     * @return array
     */
    protected function getS3Creds()
    {
        return include 'data/s3creds.config.php';
    }

    /**
     * The FTP Test Credentials
     * 
     * @return array
     */
    protected function getFtpCreds()
    {
        return include 'data/ftpcreds.config.php';
    }

    /**
     * The SFTP Test Credentials
     * 
     * @return array
     */
    protected function getSftpCreds()
    {
        return include 'data/sftpcreds.config.php';
    }

    /**
     * The Google Cloud Storage Test Credentials
     */
    protected function getGcsCreds()
    {
        return include 'data/gcscreds.config.php';
    }

    /**
     * The Google Cloud Storage Test Credentials
     */
    protected function getRcfCreds()
    {
        return include 'data/rcfcreds.config.php';
    }

    /**
     * The Databaes Test Credentiasl
     * 
     * @return array
     */
    protected function getDbCreds()
    {
        return include 'data/db.config.php';
    }

    /**
     * The SQL string to create the test table
     * 
     * @return string
     */
    protected function getSettingsTableCreateSql()
    {
        return include 'data/db.sql.php';
    }

    /**
     * The Dropbox API testing detalis
     * 
     * @return array
     */
    protected function getDropboxCreds()
    {
        return include 'data/dropboxcreds.config.php';
    }

    /**
     * Returns the path to the log file for testing Log oject
     * 
     * @return string
     */
    protected function getPathToLogFile()
    {
        return $this->dataPath() . DIRECTORY_SEPARATOR . 'm62.ut.log';
    }
}