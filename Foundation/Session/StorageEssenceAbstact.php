<?php

namespace Panda\Foundation\Session;

use Panda\Foundation\Support\EssenceWriteableAbstract;

abstract class StorageEssenceAbstact extends EssenceWriteableAbstract
{
    protected $session_name;

    protected $storage_name;

    public function __construct($storage_name = 'panda.app', $session_name = 'panda.id')
    {
        $this->session_name = $session_name;
        $this->storage_name = $storage_name;

        $this->open();

        $this->container = array_key_exists(
            $this->storage_name, $_SESSION
        ) ? $_SESSION[$this->storage_name] : array();

        $this->close();
    }

    public function save()
    {
        $this->open();

        $_SESSION[$this->session_name] = $this->container; 

        $this->close();
    }

    public function remove()
    {
        $this->open();

        unset($_SESSION[$this->session_id], $this->container);

        $this->close();
    }

    protected function open()
    {
        session_name($this->session_name); session_start();
    }

    protected function close()
    {
        session_write_close();
    }
}