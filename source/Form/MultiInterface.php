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
interface MultiInterface
{
    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     */ 
    public function __construct($type, $name, $options = [], $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function factory($type, $name, $options = [], $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function select($name, $options = [], $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $name
     *  @var string $options
     *  @var mixed  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function radio($name, $options = [], $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var array  $attr
     *  @var mixed  $value
     *
     *  @return \Panda\Form\Input
     */
    public static function checkbox($name, $options = [], $attr = [], $value = null);

    /**
     *  comment ...
     *
     *  @var mixed $value
     *  @var mixed $label
     *
     *  @return mixed
     */
    public function option($value, $label = null);

    /**
     *  comment ...
     *
     *  @return array
     */
    public function options();
}
