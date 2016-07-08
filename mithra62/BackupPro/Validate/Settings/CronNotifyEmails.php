<?php
/**
 * mithra62 - Backup Pro
 *
 * @copyright	Copyright (c) 2016, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Validate/Settings/CronNotifyEmails.php
 */
namespace mithra62\BackupPro\Validate\Settings;

use mithra62\BackupPro\Validate\AbstractField;

/**
 * Backup Pro - Cron Notify Emails Validation Object
 *
 * Validates the Cron Notify Emails setting value
 *
 * @package BackupPro\Validate
 * @author Eric Lamb <eric@mithra62.com>
 */
class CronNotifyEmails extends AbstractField
{
    /**
     * The name of the field
     * @var string
     */
    protected $field_name = 'cron_notify_emails';
    
    /**
     * (non-PHPdoc)
     * @see \mithra62\BackupPro\Validate\AbstractField::compileRules()
     */
    public function compileRules()
    {
        $emails = $this->getFieldData();
        if ($emails != '') {
            if (! is_array($emails)) {
                $emails = explode("\n", $emails);
            }
        
            foreach ($emails as $email) {
                if (! filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                    $this->setupRule('false', '"' . trim($email) . '" isn\'t a valid email');
                    // break;
                }
            }
        }
        
        return $this;
    }
}