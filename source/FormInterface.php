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
interface FormInterface
{
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
    public function input($type, $name = null, $options = array(), $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function hidden($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function text($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function email($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function password($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function file($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function textarea($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function radio($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public function checkbox($name = null, $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public function select($name = null, $options = array(), $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public function multiradio($name = null, $options = array(), $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Multi
     */
    public function multicheckbox($name = null, $options = array(), $attr = array(), $value = null);

    /**
     *  comment ...
     *
     *  @var string $addon
     *
     *  @return \Panda\Form\Input
     */
    public function open($attr = array());

    /**
     *  comment ...
     *
     *  @return string
     */
    public function close();

    /**
     *  comment ...
     *
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form
     */
    public function attr($attr = null, $equal = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form
     */
    public function set($key, $equal = null);

    /**
     *  comment ...
     *
     *  @return \Panda\Form
     */
    public function sanitize();

    /**
     *  comment ...
     *
     *  @return \Panda\Form
     */
    public function validate();

    /**
     *  comment ...
     *
     *  @return bool
     */
    public function valid();

    /**
     *  comment ...
     *
     *  @return array
     */
    public function error();

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function get($key);

    /**
     *  comment ...
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __get($key);

    /**
     *  comment ...
     *
     *  @return array
     */
    public function all();
}
