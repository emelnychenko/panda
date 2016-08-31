<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
interface InputInterface
{
    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     */ 
    public function __construct($type, $name, $attr = [], $value = null);

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
    public static function factory($type, $name, $attr = [], $value = null);

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
    public static function hidden($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function text($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function email($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function password($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function radio($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function checkbox($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function textarea($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function file($name, $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Input
     */
    public function attr($attr = null, $equal = null);

    /**
     *  comment ...
     *
     *  @var mixed $type
     *
     *  @return \Panda\Form\Input
     */
    public function type($type = null);

    /**
     *  comment ...
     *
     *  @var mixed $name
     *
     *  @return \Panda\Form\Input
     */
    public function name($name = null);

    /**
     *  comment ...
     *
     *  @return mixed
     */
    public function get();

    /**
     *  comment ...
     *
     *  @var mixed $value
     *
     *  @return \Panda\Form\Input
     */
    public function set($value);

    /**
     *  comment ...
     *
     *  @return \Panda\Form\Input
     */
    public function validate();

    /**
     *  Return decision of validate() action
     *
     *  @var mixed $type
     *
     *  @return \Panda\Form\Input
     */
    public function valid();

    /**
     *  Clear input value using global filter logic.
     *
     *  @return \Panda\Form\Input
     */
    public function sanitize();

    /**
     *  Similar sanitize()
     *
     *  @return \Panda\Form\Input
     */
    public function clear();

    /**
     *  Reset input configurator by keys
     *
     *  @var array $keys
     *
     *  @return \Panda\Form\Input
     */
    public function cleanup(
        $keys = [
            'attr', 'filter', 'report', 'value', 'error'
        ]
    );

    /**
     *  Apending filter variables by key
     *
     *  @var mixed $type
     *
     *  @return \Panda\Form\Input
     */
    public function filter($filter, $rule = null);
    /**
     *  Get all filter map
     *
     *  @return array
     */
    public function filters();

    /**
     *  Сombined method for get of set error message.
     *
     *  @var string $message
     *
     *  @return \Panda\Form\Input
     */
    public function error($message = null);
}
