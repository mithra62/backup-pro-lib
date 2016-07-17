<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/BackupPro.php
 */
namespace mithra62\BackupPro;

/**
 * Backup Pro - Error Handler Object
 *
 * Handles Backup Pro errors
 * 
 * HEAVILY inspired by Yii::ErrorHandler
 * @link https://github.com/yiisoft/yii2/blob/master/framework/base/ErrorHandler.php
 *
 * @package BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class ErrorHandler 
{
    /**
     * @var boolean whether to discard any existing page output before error display. Defaults to true.
     */
    public $discard_existing_output = true;
    
    /**
     * @var integer the size of the reserved memory. A portion of memory is pre-allocated so that
     * when an out-of-memory issue occurs, the error handler is able to handle the error with
     * the help of this reserved memory. If you set this value to be 0, no memory will be reserved.
     * Defaults to 256KB.
     */
    public $memory_reserve_size = 262144;
    
    /**
     * @var \Exception the exception that is being handled currently.
     */
    public $exception;
    
    /**
     * @var string Used to reserve memory for fatal error handler.
     */
    private $memory_reserve;
    
    /**
     * @var \Exception from HHVM error that stores backtrace
     */
    private $hhvm_exception;
    
    /**
     * Register this error handler
     */
    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        
        if (defined('HHVM_VERSION')) {
            set_error_handler([$this, 'handleHhvmError']);
        } else {
            set_error_handler([$this, 'handleError']);
        }
        
        if ($this->memory_reserve_size > 0) {
            $this->memory_reserve = str_repeat('x', $this->memory_reserve_size);
        }
        
        register_shutdown_function([$this, 'handleFatalError']);
    }   
    
    /**
     * Unregisters this error handler by restoring the PHP error and exception handlers.
     */
    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }    
}