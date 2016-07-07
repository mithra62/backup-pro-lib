<?php
/**
 * mithra62 - Backup Pro
 *
 * @author		Eric Lamb <eric@mithra62.com>
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		3.0
 * @filesource 	./mithra62/BackupPro/Notify.php
 */
namespace mithra62\BackupPro;

use JaegerApp\Exceptions\EmailException;

/**
 * Backup Pro - Notification Object
 *
 * Contains the methods for sending notificaitons
 *
 * @package BackupPro
 * @author Eric Lamb <eric@mithra62.com>
 */
class Notify
{

    /**
     * The mail object
     * 
     * @var \mithra62\Email
     */
    protected $mail = null;

    /**
     * The Backup object
     * 
     * @var \mithra62\BackupPro\Backup
     */
    protected $backup = null;

    /**
     * The Configuration details
     * 
     * @var array
     */
    protected $settings = array();

    /**
     * The path to where view scripts are stored
     * 
     * @var string
     */
    protected $view_path = null;

    /**
     * Sets the Mail object
     * 
     * @param \mithra62\Email $mail            
     * @return \mithra62\BackupPro\Notify
     */
    public function setMail(\mithra62\Email $mail)
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * Returns the mail object
     * 
     * @return \mithra62\Email
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Returns the Backup object
     * 
     * @return \mithra62\BackupPro\Backup $backup
     */
    public function getBackup()
    {
        return $this->backup;
    }

    /**
     * Sets the Backup object
     * 
     * @param \mithra62\BackupPro\Backup $backup            
     * @return \mithra62\BackupPro\Notify
     */
    public function setBackup(\mithra62\BackupPro\Backup $backup)
    {
        $this->backup = $backup;
        return $this;
    }

    /**
     * Sets teh Settings array
     * 
     * @param array $settings            
     * @return \mithra62\BackupPro\Notify
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Sets the email view path
     * 
     * @param string $path            
     * @return \mithra62\BackupPro\Notify
     */
    public function setViewPath($path)
    {
        $this->view_path = $path;
        return $this;
    }

    /**
     * Returns the View path
     * 
     * @return string
     */
    public function getViewPath()
    {
        return $this->view_path;
    }

    /**
     * Sends the email notification upon Cron completion
     * 
     * @param array $emails            
     * @param array $backup_paths            
     * @param string $backup_type            
     * @throws EmailException
     * @throws \Exception
     */
    public function sendBackupCronNotification(array $emails, array $backup_paths, $backup_type = 'database')
    {
        try {
            $backup_details = array();
            foreach ($backup_paths as $type => $backup) {
                $path_parts = pathinfo($backup);
                if (! empty($path_parts['dirname']) && ! empty($path_parts['basename']) && ! empty($path_parts['filename']) && ! empty($path_parts['extension'])) {
                    $backup_details = $this->backup->getDetails()->getDetails($path_parts['basename'], $path_parts['dirname']);
                }
            }
            
            $services = $this->backup->getServices();
            
            $vars = array(
                'backup_paths' => $backup_paths,
                'backup_details' => $backup_details,
                'backup_type' => $backup_type,
                'site_name' => $services['platform']->getSiteName(),
                'site_url' => $services['platform']->getSiteUrl()
            );
            
            $email = $this->getMail()
                ->setSubject($this->settings['cron_notify_email_subject'])
                ->setMessage($this->settings['cron_notify_email_message'])
                ->setTo($emails)
                ->setMailtype($this->settings['cron_notify_email_mailtype']);
            $email->send($vars);
        } catch (EmailException $e) {
            $e->logException($e);
            throw new EmailException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Sends the backup state notification
     * 
     * @param array $emails            
     * @param array $backup_meta            
     * @param array $errors            
     */
    public function sendBackupState(array $emails, array $backup_meta, array $errors)
    {
        try {
            $type = 'files';
            $frequency = $this->settings['file_backup_alert_threshold'];
            if (isset($errors['backup_state_db_backups'])) {
                $type = 'database';
                $frequency = $this->settings['db_backup_alert_threshold'];
            }
            
            $services = $this->backup->getServices();
            $vars = array(
                'last_backup_date' => $backup_meta[$type]['newest_backup_taken'],
                'backup_frequency' => $frequency,
                'backup_type' => $type,
                'site_name' => $services['platform']->getSiteName(),
                'site_url' => $services['platform']->getSiteUrl()
            );
            
            $email = $this->getMail()
                ->setConfig($services['platform']->getEmailConfig())
                ->setSubject($this->settings['backup_missed_schedule_notify_email_subject'])
                ->setMessage($this->settings['backup_missed_schedule_notify_email_message'])
                ->setTo($emails)
                ->setMailtype($this->settings['backup_missed_schedule_notify_email_mailtype']);
            
            $email->send($vars);
        } catch (EmailException $e) {
            $e->logException($e);
            throw new EmailException($e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}