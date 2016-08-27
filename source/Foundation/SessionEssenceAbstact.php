<?php

namespace Panda\Foundation;

abstract class SessionEssenceAbstact extends EssenceWriteableAbstract implements SessionEssenceInterface
{
    protected $session;

    protected $storage;

    public function __construct($storage = 'panda.app', $session = 'panda.id')
    {
        $this->session = $session;
        $this->storage = $storage;

        $this->open();

        $this->container = array_key_exists(
            $this->storage, $_SESSION
        ) ? $_SESSION[$this->storage] : array();

        $this->close();
    }

    public function save()
    {
        $this->open();

        $_SESSION[$this->session] = $this->container; 

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
        session_name($this->session); session_start();
    }

    protected function close()
    {
        session_write_close();
    }
}