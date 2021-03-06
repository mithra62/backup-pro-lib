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

use JaegerApp\Traits\Log;

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
 * @since 3.4
 */
class ErrorHandler 
{
    use Log;
    
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
     * Is debug mode enabled
     * @var bool
     */
    private $debug_mode = false;

    /**
     * Register this error handler
     * @return ErrorHandler
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
        return $this;
    }   
    
    /**
     * Unregisters this error handler by restoring the PHP error and exception handlers.
     * @return ErrorHandler
     */
    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
        return $this;
    } 
    
    public function renderException($exception) 
    {
        // an other exception could be thrown while displaying the exception
        $msg = "An Error occurred while backing up
                -:\n";
        $msg .= (string) $exception;
        if ($this->getDebugMode()) {
            if (PHP_SAPI === 'cli') {
                echo $msg . "\n";
            } else {
                echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES) . '</pre>';
                $msg .= "\n\$_SERVER = " . print_r($_SERVER, true);
                echo '<pre>'.$msg.'</pre>';
            }
        } else {
            echo 'An internal server error occurred.';
        }
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
            $this->logError($exception);
            if ($this->discard_existing_output) {
                $this->clearOutput();
            }
            
            $this->renderException($exception);
            if ($this->getDebugMode()) {
                if (defined('HHVM_VERSION')) {
                    flush();
                }
                exit(1);
            }

        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $msg = "An Error occurred while handling another error:\n";
            $msg .= (string) $e;
            $msg .= "\nPrevious exception:\n";
            $msg .= (string) $exception;
            if ($this->getDebugMode()) {
                if (PHP_SAPI === 'cli') {
                    echo $msg . "\n";
                } else {
                    echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES) . '</pre>';
                }
            } else {
                echo 'An internal server error occurred.';
            }
            $msg .= "\n\$_SERVER = " .print_r($_SERVER, true);
            error_log($msg);
            if (defined('HHVM_VERSION')) {
                flush();
            }
            exit(1);
        }
        $this->exception = null;
    }   
    
    /**
     * Handles HHVM execution errors such as warnings and notices.
     *
     * This method is used as a HHVM error handler. It will store exception that will
     * be used in fatal error handler
     *
     * @param integer $code the level of the error raised.
     * @param string $message the error message.
     * @param string $file the filename that the error was raised in.
     * @param integer $line the line number the error was raised at.
     * @param mixed $context
     * @param mixed $backtrace trace of error
     * @return boolean whether the normal error handler continues.
     *
     * @throws ErrorException
     */
    public function handleHhvmError($code, $message, $file, $line, $context, $backtrace)
    {
        if ($this->handleError($code, $message, $file, $line)) {
            return true;
        }
        if (E_ERROR & $code) {
            $exception = new ErrorHandler\ErrorException($message, $code, $code, $file, $line);
            $ref = new \ReflectionProperty('\Exception', 'trace');
            $ref->setAccessible(true);
            $ref->setValue($exception, $backtrace);
            $this->_hhvmException = $exception;
        }
        return false;
    }
    /**
     * Handles PHP execution errors such as warnings and notices.
     *
     * This method is used as a PHP error handler. It will simply raise an [[ErrorException]].
     *
     * @param integer $code the level of the error raised.
     * @param string $message the error message.
     * @param string $file the filename that the error was raised in.
     * @param integer $line the line number the error was raised at.
     * @return boolean whether the normal error handler continues.
     *
     * @throws ErrorException
     */
    public function handleError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            // load ErrorException manually here because autoloading them will not work
            // when error occurs while autoloading a class
            if (!class_exists('mithra62\\BackupPro\\ErrorHandler\\ErrorException', false)) {
                require_once(__DIR__ . '/ErrorHandler/ErrorException.php');
            }
            $exception = new ErrorHandler\ErrorException($message, $code, $code, $file, $line);
            // in case error appeared in __toString method we can't throw any exception
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_shift($trace);
            foreach ($trace as $frame) {
                if ($frame['function'] === '__toString') {
                    $this->handleException($exception);
                    if (defined('HHVM_VERSION')) {
                        flush();
                    }
                    exit(1);
                }
            }
            throw $exception;
        }
        return false;
    }
    
    /**
     * Handles fatal PHP errors
     */
    public function handleFatalError()
    {
        unset($this->_memoryReserve);
        // load ErrorException manually here because autoloading them will not work
        // when error occurs while autoloading a class
        if (!class_exists('mithra62\\BackupPro\\ErrorHandler\\ErrorException', false)) {
            require_once(__DIR__ . '/ErrorHandler/ErrorException.php');
        }
        
        $error = error_get_last();
        if (ErrorHandler\ErrorException::isFatalError($error)) {
            if (!empty($this->_hhvmException)) {
                $exception = $this->_hhvmException;
            } else {
                $exception = new ErrorHandler\ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            }
            $this->exception = $exception;
            $this->logError($exception);
            if ($this->discard_existing_output) {
                $this->clearOutput();
            }
            $this->renderException($exception);
            // need to explicitly flush logs because exit() next will terminate the app immediately
            if (defined('HHVM_VERSION')) {
                flush();
            }
            exit(1);
        }
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
        if ($exception instanceof Exception) {
            $message = "{$exception->getName()}: {$exception->getMessage()}";
        } elseif ($this->getDebugMode()) {
            if ($exception instanceof Exception) {
                $message = "Exception ({$exception->getName()})";
            } elseif ($exception instanceof ErrorHandler\ErrorException) {
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
    

    /**
     * @return the $debug_mode
     */
    public function getDebugMode()
    {
        return $this->debug_mode;
    }
    
    /**
     * Enable debug mode
     * @param boolean $debug_mode
     * @return ErrorHandler
     */
    public function setDebugMode($debug_mode)
    {
        $this->debug_mode = $debug_mode;
        return $this;
    }    
}