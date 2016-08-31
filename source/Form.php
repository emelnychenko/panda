<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Form\Input;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Form
{
    protected $default      = array();

    protected $attr         = array();

    protected $input        = array();

    protected $validated    = array();

    protected $valid        = true;

    protected $error        = array();

    public static function factory()
    {
        return new static();
    }

    public function input($type, $name = null, array $attr = array(), $value = null)
    {
        if (
            $type !== null && $name === null
        ) {
            return $this->input[$type];
        }

        return $this->input[$name] = Input::factory(
            $type, $name, array_replace(
                $this->default, $attr
            ), $value
        );
    }

    public function set($key, $equal = null)
    {
        panda_iterator($key, $equal, function($key, $equal) {
            array_key_exists($key, $this->input) ? $this->input[$key]->set($equal) : null;
        });

        return $this;
    }

    public function sanitize()
    {
        foreach ($this->input as $input) {
            $input->sanitize();
        }

        return $this;
    }

    public function validate()
    {
        $this->valid = true;

        foreach ($this->input as $name => $input) {
            $this->validated[$name] = $valid = $input->validate()->valid();

            if (
                $valid === false
            ) {
                $this->valid        = false;
                $this->error[$name] = $input->error();
            }
        }

        return $this;
    }

    public function valid()
    {
        return $this->valid;
    }

    public function error()
    {
        return $this->error;
    }

    public function get($key)
    {
        return array_key_exists($key, $this->input) ? $this->input[$key]->get() : null;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function all()
    {
        $shared = array();

        foreach ($this->input as $key => $instance) {
            $shared[$key] = $instance->get();
        }

        return $shared;
    }
}
