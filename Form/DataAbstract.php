<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

use Panda\Http\RequestInterface;
use Panda\Alloy\Factory;
use Panda\Form\Data\Input as Input;
use Panda\Essence\ReadableAbstract;

/**
 *  Panda Form Data Abstract
 *
 *  @subpackage Form
 */
class DataAbstract extends ReadableAbstract implements Factory
{
    /**
     *  @var bool
     */
    protected $valid        = true;

    /**
     *  @var array
     */
    protected $error        = [];

    /**
     *  @var array
     */
    protected $shared       = [];

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
    public function input($name, $equal = null)
    {
        return $this->shared[$name] = Input::factory($equal);
    }

    /**
     *  comment ...
     *
     *  @return \Panda\Form
     */
    public function sanitize()
    {
        foreach ($this->shared as $name => $input) {
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

        foreach ($this->shared as $name => $input) {
            if ($input->validate()->valid() === false) {
                $this->valid = false;
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
        $errors = [];

        foreach ($this->shared as $name => $input) {
            if (($error = $input->error()) !== null) {
                $errors[$name] = $error;
            }
        }

        return $errors;
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function get($input, $default = null)
    {
        return array_key_exists($input, $this->shared) ? $this->shared[$input]->get() : $default;
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function set($input, $equal = null)
    {
        $inputs = is_array($input) ? $input : [$input => $equal];

        foreach ($inputs as $input => $equal) {
            array_key_exists($input, $this->shared) ? $this->shared[$input]->set($equal) : null;
        }

        return $this;
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __get($input)
    {
        return $this->get($input);
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __set($input, $equal = null)
    {
        return $this->set($input, $equal);
    }

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __isset($input)
    {
        return array_key_exists($input, $this->shared);
    }

    /**
     *  comment ...
     *
     *  @return array
     */
    public function all()
    {
        $shared = [];

        foreach ($this->shared as $name => $input) {
            $shared[$name] = $input->get();
        }

        return $shared;
    }

    public function requested(RequestInterface $request)
    {
        return $this->set(
            $request->all()
        )->validate()->valid();
    }
}
