<?php
/**
 * mithra62
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Browser/EE2/StorageTest.php
 */
 
namespace mithra62\BackupPro\tests\Browser\EE2;

use mithra62\BackupPro\tests\Browser\StorageTestAbstract;
use mithra62\BackupPro\tests\Browser\EE2Trait;
 
/**
 * mithra62 - ExpressionEngine 2 Browser (Selenium) Storage object Unit Tests
 *
 * Defines the values the Settings Selenium tests will use to execute the test
 *
 * @package 	mithra62\Tests
 * @author		Eric Lamb <eric@mithra62.com>
 */
class StorageTest extends StorageTestAbstract 
{
    use EE2Trait;
    
    /**
     * The URLs to test the ExpressionEngine 2 Settings page
     *
     * Note that the ExpressionEngine 2 site MUST be configured to use cookies only for authentication
     * @var array
     */
    public $urls = array(
        'storage_view_storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?/cp/addons_modules/show_module_cp&module=backup_pro&method=view_storage',
        'storage_add_email_storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?S=0&D=cp&C=addons_modules&M=show_module_cp&module=backup_pro&method=new_storage&engine=email',
        'storage_add_ftp_storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?S=0&D=cp&C=addons_modules&M=show_module_cp&module=backup_pro&method=new_storage&engine=ftp',
        'storage_add_gcs_storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?S=0&D=cp&C=addons_modules&M=show_module_cp&module=backup_pro&method=new_storage&engine=gcs',
        'storage_add_local_storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?S=0&D=cp&C=addons_modules&M=show_module_cp&module=backup_pro&method=new_storage&engine=local',
        'storage_add_rcf_storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?S=0&D=cp&C=addons_modules&M=show_module_cp&module=backup_pro&method=new_storage&engine=rcf',
        'storage_add_s3storage' => 'http://eric.ee2.clean.mithra62.com/admin.php?S=0&D=cp&C=addons_modules&M=show_module_cp&module=backup_pro&method=new_storage&engine=s3',
    );
    

    /**
     * Disable this since we want full browser support
     */
    public function setUp()
    {
    
    }
    
    /**
     * Disable this since we want full browser support
     */
    public function teardown()
    {
    
    }
    
}