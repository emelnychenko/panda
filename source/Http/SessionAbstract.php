<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

use Panda\Alloy\FactoryInterface    as Factory;
use Panda\Alloy\StorageInterface    as Storage;
use Panda\Essence\Writeable         as Essence;

/**
 *  Http Session Abstract
 *
 *  @subpackage Http
 */
abstract class SessionAbstract extends Essence implements Factory, Storage
{
    /**
     *  @var string
     */
    protected $name             = 'panda.session';

    /**
     *  @var string (json|php)
     */
    protected $serialization    = 'json';

    /**
     *  @var string 
     */
    protected $input            = null;

    /**
     *  Construct instance.
     *
     *  @return \Panda\Http\SessionAbstract
     */
    public function __construct($name = null)
    {
        $this->name = $name = isset($name) ? $name : $this->name;

        $this->enclosed(function() use ($name) {
            $this->input = $input = array_key_exists($name, $_SESSION) ? $_SESSION[$name] : null;

            $decoded = $this->serialization === 'php' ? 
                @unserialize($input) : @json_decode($input, true);

            if ($decoded !== null && $decoded !== false) {
                $this->replace(is_array($decoded) ? $decoded : ['equal' => $decoded]);
            }
        });
    }

    /**
     *  Factory instance.
     *
     *  @var mixed $name
     *
     *  @return \Panda\Http\SessionAbstract
     */
    public static function factory($name = null) {
        return new static($name);
    }

    /**
     *  Return cookie name param.
     *
     *  @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     *  Short call update method.
     *
     *  @return \Panda\Http\SessionAbstract
     */
    public function update($shared, $equal = null)
    {
        $this->set($shared, $equal)->save();
    }

    /**
     *  Saving data handler.
     *
     *  @return \Panda\Http\SessionAbstract
     */
    public function save()
    {
        $this->input = $input = $this->serialization === 'php' ? 
            serialize($this->shared) : json_encode($this->shared);

        $this->enclosed(function() use ($input) {
            $_SESSION[$this->name] = $input; 
        });

        return $this;
    }

    /**
     *  Remove call.
     *
     *  @return \Panda\Http\SessionAbstract
     */
    public function remove()
    {
        $this->enclosed(function() {
            $this->input    = null; 
            $this->shared   = [];

            unset($_SESSION[$this->name]);
        });

        return $this;
    }

    /**
     *  Open close enclosed execution.
     *
     *  @return \Panda\Http\SessionAbstract
     */
    protected function enclosed(callable $callback)
    {
        $opened = session_id() !== '';

        if ($opened === false) {
            session_start();
        }

        call_user_func($callback);

        if ($opened === false) {
            session_write_close();
        }

        return $this;
    }
}
