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
    
    /**
     * Handles uncaught PHP exceptions.
     *
     * This method is implemented as a PHP exception handler.
     *
     * @param \Exception $exception the exception that is not caught
     */
    public function handleException($exception)
    {
        
        $this->exception = $exception;
        
        // disable error capturing to avoid recursive errors while handling exceptions
        $this->unregister();
        
        // set preventive HTTP status code to 500 in case error handling somehow fails and headers are sent
        // HTTP exceptions will override this value in renderException()
        if (PHP_SAPI !== 'cli') {
            http_response_code(500);
        }
        
        try {
            $this->logException($exception);
            if ($this->discard_existing_output) {
                $this->clearOutput();
            }
            
            $this->renderException($exception);

        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $msg = "An Error occurred while handling another error:\n";
            $msg .= (string) $e;
            $msg .= "\nPrevious exception:\n";
            $msg .= (string) $exception;
            if (YII_DEBUG) {
                if (PHP_SAPI === 'cli') {
                    echo $msg . "\n";
                } else {
                    echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, Yii::$app->charset) . '</pre>';
                }
            } else {
                echo 'An internal server error occurred.';
            }
            $msg .= "\n\$_SERVER = " . VarDumper::export($_SERVER);
            error_log($msg);
            if (defined('HHVM_VERSION')) {
                flush();
            }
            exit(1);
        }
        $this->exception = null;
    }    
    
    /**
     * Removes all output echoed before calling this method.
     */
    public function clearOutput()
    {
        // the following manual level counting is to deal with zlib.output_compression set to On
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }    
    
    /**
     * Converts an exception into a PHP error.
     *
     * This method can be used to convert exceptions inside of methods like `__toString()`
     * to PHP errors because exceptions cannot be thrown inside of them.
     * @param \Exception $exception the exception to convert to a PHP error.
     */
    public static function convertExceptionToError($exception)
    {
        trigger_error(static::convertExceptionToString($exception), E_USER_ERROR);
    }
    
    /**
     * Converts an exception into a simple string.
     * @param \Exception $exception the exception being converted
     * @return string the string representation of the exception.
     */
    public static function convertExceptionToString($exception)
    {
        if ($exception instanceof Exception && ($exception instanceof UserException || !YII_DEBUG)) {
            $message = "{$exception->getName()}: {$exception->getMessage()}";
        } elseif (YII_DEBUG) {
            if ($exception instanceof Exception) {
                $message = "Exception ({$exception->getName()})";
            } elseif ($exception instanceof ErrorException) {
                $message = "{$exception->getName()}";
            } else {
                $message = 'Exception';
            }
            $message .= " '" . get_class($exception) . "' with message '{$exception->getMessage()}' \n\nin "
            . $exception->getFile() . ':' . $exception->getLine() . "\n\n"
                . "Stack trace:\n" . $exception->getTraceAsString();
        } else {
            $message = 'Error: ' . $exception->getMessage();
        }
        return $message;
    }
    }    
}