<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

use Panda\Form\Angular\Input as Input;

/**
*  Panda Form Angular Abstract
 *
 *  @subpackage Form
 */
class AngularAbstract extends HtmlAbstract
{
    /**
     * @var array
     */
    protected $attr = [
        'novalidate', 'name' => 'panda.form'
    ];

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

        return $this->shared[$name] = Input::factory(
            $name, $type, array_replace($this->default, $attr), $equal
        )->form($this->attr['name']);
    }
}
