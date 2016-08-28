<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */
namespace Panda\Foundation;

/**
 *  Panda Foundation Sesison Essence Abstract
 *
 *  @subpackage Framework
 */
abstract class SessionEssenceAbstact extends EssenceWriteableAbstract implements SessionEssenceInterface
{
    protected $session;

    protected $storage;

    public function __construct($storage = 'panda.app', $session = 'panda.id')
    {
        $this->session = $session;
        $this->storage = $storage;

        $this->procedure(function() {
            $this->shared = array_key_exists(
                $this->storage, $_SESSION
            ) ? $_SESSION[$this->storage] : array();
        });
    }

    public function save()
    {
        $this->procedure(function() {
            $_SESSION[$this->session] = $this->shared; 
        });
    }

    public function remove()
    {
        $this->procedure(function() {
            unset($_SESSION[$this->session_id], $this->shared);
        });
    }

    protected function procedure($callback)
    {
        session_name($this->session); session_start();   

        call_user_func($callback);

        session_write_close();
    }
}