<?php

namespace Panda\Foundation\Session;

abstract class StorageHandlerAbstract
{
    abstract public function open($savePath, $sessionName);

    abstract public function close();

    abstract public function read($id);

    abstract function write($id, $data);

    abstract function destroy($id);

    abstract function gc($maxlifetime);

    public function register()
    {
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
    }
}
