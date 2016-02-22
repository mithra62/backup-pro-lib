<?php
namespace mithra62\BackupPro;

class Rest
{
    protected $lang = null;
    
    public function getServer()
    {
        return new Rest\Server();
    }
    
    public function setLang(\mithra62\Language $lang)
    {
        $this->lang = $lang;
        return $this;
    }
}