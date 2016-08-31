<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Form\Input;
use Panda\Form\Multi;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Form implements FormInterface
{
    /**
     *  @var array
     */ 
    protected $default      = array();

    /**
     *  @var array
     */ 
    protected $attr         = array();

    /**
     *  @var string
     */ 
    protected $input        = array();

    /**
     *  @var array
     */ 
    protected $validated    = array();

    /**
     *  @var bool
     */ 
    protected $valid        = true;

    /**
     *  @var array
     */ 
    protected $error        = array();

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function factory()
    {
        return new static();
    }

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return mixed
     */
    public function input($type, $name = null, $options = array(), $attr = null, $value = null)
    {
        if (
            $type !== null && $name === null
        ) {
            return $this->input[$type];
        }

        if (
            in_array($type, array(Multi::SELECT, Multi::RADIO, Multi::CHECKBOX), true)
        ) {
            if (
                is_scalar($attr) && $value === null
            ) {
                return $this->input[$name] = Multi::factory(
                    $type, $name, $options, $this->default, $attr
                );
            }

            $attr = isset($attr) ? $attr : array();

            return $this->input[$name] = Multi::factory(
                $type, $name, $options, array_replace($this->default, $attr), $value
            );
        }

        if (
            is_scalar($options) && $attr === null
        ) {
            return $this->input[$name] = Input::factory(
                $type, $name, $this->default, $options
            );
        }

        return $this->input[$name] = Input::factory(
            $type, $name, array_replace($this->default, $options), $attr
        );
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function hidden($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::HIDDEN, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function text($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::TEXT, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function email($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::EMAIL, $name, $attr, $value);   
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function password($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::PASSWORD, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function file($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::FILE, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function textarea($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::TEXTAREA, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function radio($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::RADIO, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function checkbox($name = null, $attr = array(), $value = null)
    {
        return $this->input(Input::CHECKBOX, $name, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public function select($name = null, $options = array(), $attr = array(), $value = null)
    {
        return $this->input(Multi::SELECT, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public function multiradio($name = null, $options = array(), $attr = array(), $value = null)
    {
        return $this->input(Multi::RADIO, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public function multicheckbox($name = null, $options = array(), $attr = array(), $value = null)
    {
        return $this->input(Multi::CHECKBOX, $name, $options, $attr, $value);
    }

    /**
     *  comment ...
     *
     *  @var string $addon
     *
     *  @return \Panda\Form\Input
     */
    public function open($attr = array())
    {
        $attr = is_array($attr) ? $attr : array('method' => $attr);

        foreach (array_replace($attr, $this->attr) as $key => $value) {
            array_push(
                $attr, sprintf('%s="%s"', $key, $value)
            );
        }

        return sprintf(
            '<form %s>', implode(' ', $attr)
        );
    }

    /**
     *  comment ...
     *
     *  @return string
     */
    public function close()
    {
        return '</form>';
    }

    /**
     *  comment ...
     *
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form
     */
    public function attr($attr = null, $equal = null)
    {
        if (
            $attr === null  && $equal === null
        )  {
            return $this->attr;
        } elseif (
            is_array($attr) && $equal === null
        ) {
            foreach ($attr as $_attr => $_equal) {
                $this->attr[$_attr] = $_equal;
            }
        } elseif (
            is_scalar($attr) && is_scalar($equal)
        ) {
            $this->attr[$attr] = $equal;
        } elseif (
            is_scalar($attr) && $equal === null
        ) {
            return array_key_exists($attr, $this->attr) ? $this->attr[$attr] : null;
        }

        return $this;
    }

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form
     */
    public function set($key, $equal = null)
    {
        if (
            is_array($key) && $equal === null
        ) {
            foreach ($key as $_key => $_equal) {
                array_key_exists($_key, $this->input) ? 
                    $this->input[$_key]->set($_equal) : null;
            }
        } elseif (
            is_scalar($key) && is_scalar($equal)
        ) {
            array_key_exists($key, $this->input) ? $this->input[$key]->set($equal) : null;
        }

        return $this;
    }

    /**
     *  comment ...
     *
     *  @return \Panda\Form
     */
    public function sanitize()
    {
        foreach ($this->input as $input) {
            $input->sanitize();
        }

        return $this;
    }

    /**
     *  comment ...
     *
     *  @return \Panda\Form
     */
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

    /**
     *  comment ...
     *
     *  @return bool
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     *  comment ...
     *
     *  @return array
     */
    public function error()
    {
        return $this->error;
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function get($key)
    {
        return array_key_exists($key, $this->input) ? $this->input[$key]->get() : null;
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     *  comment ...
     *
     *  @return array
     */
    public function all()
    {
        $shared = array();

        foreach ($this->input as $key => $instance) {
            $shared[$key] = $instance->get();
        }

        return $shared;
    }
}
