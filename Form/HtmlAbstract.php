<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

use Panda\Form\Html\Input as Input;
use Panda\Form\Html\Multi as Multi;

/**
 *  Panda Form Html Abstract
 *
 *  @subpackage Form
 */
class HtmlAbstract extends DataAbstract
{
    /**
     *  @var array
     */
    protected $default      = [];

    /**
     *  @var array
     */
    protected $attr         = [];

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
    public function input($name = 'panda.input', $type = 'text', array $attr = [], $equal = null)
    {
        if (array_key_exists($name, $this->shared)) {
            return $this->shared[$name];
        }

        if ($type === 'select') {
            return $this->shared[$name] = Multi::factory($name, $type, array_replace($this->default, $attr), $equal);
        }

        return $this->shared[$name] = Input::factory($name, $type, array_replace($this->default, $attr), $equal);
    }
    
    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function hidden($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::HIDDEN, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function text($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::TEXT, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function email($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::EMAIL, $attr, $equal);   
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function time($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::TIME, $attr, $equal);   
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function date($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::DATE, $attr, $equal);   
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function password($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::PASSWORD, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function file($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::FILE, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function textarea($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::TEXTAREA, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function radio($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::RADIO, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function checkbox($name, $attr = [], $equal = null)
    {
        return $this->input($name, Input::CHECKBOX, $attr, $equal);
    }

    /**
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Base\Input
     */
    public function select($name, $attr = [], $equal = null)
    {
        return $this->input($name, Multi::SELECT, $attr, $equal);
    }

    // public function select($name = null, $options = [], $attr = [], $value = null)
    // {
    //     return $this->input(Multi::SELECT, $name, $options, $attr, $value);
    // }

    // public function multiradio($name = null, $options = [], $attr = [], $value = null)
    // {
    //     return $this->input(Multi::RADIO, $name, $options, $attr, $value);
    // }

    // public function multicheckbox($name = null, $options = [], $attr = [], $value = null)
    // {
    //     return $this->input(Multi::CHECKBOX, $name, $options, $attr, $value);
    // }

    /**
     *  @param  array $attr
     * 
     *  @return string
     */
    public function open($attr = [])
    {
        $arnd = [];
        $attr = is_array($attr) ? $attr : ['method' => $attr];

        foreach (array_replace($this->attr, $attr) as $key => $equal) {
            array_push($arnd, is_numeric($key) ? $equal : sprintf("%s=\"%s\"", $key, $equal));
        }

        return sprintf('<form %s>', implode(' ', $arnd));
    }

    /**
     *  @return string
     */
    public function close()
    {
        return '</form>';
    }

    /**
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\BaseAbstract
     */
    public function attr($attr, $equal = null)
    {
        $attr = is_array($attr) ? $attr : (
            $equal === null ? [$attr => $attr] : [$attr => $equal]
        );

        $this->attr = array_replace($this->attr, $attr);

        return $this;
    }
}
