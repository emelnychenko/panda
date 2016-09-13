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
class Input implements InputInterface
{
    /**
     *  @const HIDDEN
     */ 
    const HIDDEN            = 'hidden';

    /**
     *  @const TEXT
     */ 
    const TEXT              = 'text';

    /**
     *  @const EMAIL
     */ 
    const EMAIL             = 'email';

    /**
     *  @const PASSWORD
     */ 
    const PASSWORD          = 'password';

    /**
     *  @const RADIO
     */ 
    const RADIO             = 'radio';

    /**
     *  @const CHECKBOX
     */ 
    const CHECKBOX          = 'checkbox';

    /**
     *  @const TEXTAREA
     */ 
    const TEXTAREA          = 'textarea';

    /**
     *  @const EMAIL
     */ 
    const FILE              = 'file';

    /**
     *  @const EMAIL
     */ 
    const DATE              = 'date';

    /**
     *  @const EMAIL
     */ 
    const TIME              = 'time';

    /**
     *  @var string
     */ 
    protected $type         = 'text';

    /**
     *  @var string
     */ 
    protected $name         = null;

    /**
     *  @var array
     */ 
    protected $attr         = array();

    /**
     *  @var string
     */ 
    protected $value        = null;

    /**
     *  @var string
     */ 
    protected $filters      = array();

    /**
     *  @var array
     */ 
    protected $report       = array();

    /**
     *  @var string
     */ 
    protected $valid        = true;

    /**
     *  @var string
     */ 
    protected $error        = null;

    /**
     *  comment ...
     *
     *  @var string $type
     *  @var string $name
     *  @var mixed  $attr
     *  @var mixed  $value
     */ 
    public function __construct($type, $name, $attr = [], $value = null)
    {
        $this->type     = $type; 
        $this->name     = $name;

        if (
            is_scalar($attr)
        ) {
            $this->set($attr);
        } else {
            $this->attr = $attr;
            $this->set($value);
        }
    }

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
    public static function factory($type, $name, $attr = [], $value = null)
    {
        return new static($type, $name, $attr, $value);
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
    public static function hidden($name, $attr = [], $value = null)
    {
        return new static(
            static::HIDDEN, $name, $attr, $value
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
    public static function text($name, $attr = [], $value = null)
    {
        return new static(
            static::TEXT, $name, $attr, $value
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
    public static function time($name, $attr = [], $value = null)
    {
        return new static(
            static::TIME, $name, $attr, $value
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
    public static function date($name, $attr = [], $value = null)
    {
        return new static(
            static::DATE, $name, $attr, $value
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
    public static function email($name, $attr = [], $value = null)
    {
        return new static(
            static::EMAIL, $name, $attr, $value
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
    public static function password($name, $attr = [], $value = null)
    {
        return new static(
            static::PASSWORD, $name, $attr, $value
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
    public static function radio($name, $attr = [], $value = null)
    {
        return new static(
            static::RADIO, $name, $attr, $value
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
    public static function checkbox($name, $attr = [], $value = null)
    {
        return new static(
            static::CHECKBOX, $name, $attr, $value
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
    public static function textarea($name, $attr = [], $value = null)
    {
        return new static(
            static::TEXTAREA, $name, $attr, $value
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
    public static function file($name, $attr = [], $value = null)
    {
        return new static(
            static::FILE, $name, $attr, $value
        );
    }

    /**
     *  comment ...
     *
     *  @var mixed  $attr
     *  @var mixed  $equal
     *
     *  @return \Panda\Form\Input
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
     *  @var mixed $type
     *
     *  @return \Panda\Form\Input
     */
    public function type($type = null)
    {
        if ($type === null) {
            return $this->type;
        }

        $this->type = $type;

        return $this;
    }

    /**
     *  comment ...
     *
     *  @var mixed $name
     *
     *  @return \Panda\Form\Input
     */
    public function name($name = null)
    {
        if ($name === null) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    /**
     *  comment ...
     *
     *  @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     *  comment ...
     *
     *  @var mixed $value
     *
     *  @return \Panda\Form\Input
     */
    public function set($value)
    {
        if (
            is_scalar($value) && $this->type !== 'textarea'
        ) {
            $this->value = htmlspecialchars(
                stripslashes(
                    trim(
                        $value
                    )
                )
            );
        } elseif (
            is_scalar($value) && $this->type === 'textarea'
        ) {
            $this->value = stripslashes(
                trim(
                    $value
                )
            );
        } elseif (
            $value === null
        ) {
            $this->value = $value;
        }

        return $this;
    }

    /**
     *  comment ...
     *
     *  @return \Panda\Form\Input
     */
    public function validate()
    {
        $this->valid = true;

        foreach ($this->filters as $filter => $mixed) {
            $valid = true;

            if (
                is_callable($mixed)
            ) {
                $this->report[$filter] = $valid = call_user_func($mixed, $this->value);
            } elseif (
                method_exists(Validation::class, $filter)
            ) {
                $mixed = is_array($mixed) ? $mixed : [$mixed];

                array_unshift($mixed, $this->value);

                $this->report[$filter] = $valid = forward_static_call_array([
                    Validation::class, $filter 
                ], $mixed);
            }

            if ($valid === false) {
                $this->valid = false;
            }
        }

        return $this;
    }

    /**
     *  Return decision of validate() action
     *
     *  @var mixed $type
     *
     *  @return \Panda\Form\Input
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     *  Clear input value using global filter logic.
     *
     *  @return \Panda\Form\Input
     */
    public function sanitize()
    {
        foreach ($this->filters as $filter => $mixed) {
            if (
                method_exists(Sanitation::class, $filter)
            ) {
                $mixed = is_array($mixed) ? $mixed : [$mixed];

                array_unshift($mixed, $this->value);

                $this->value = forward_static_call_array([
                    Sanitation::class, $filter 
                ], $mixed);
            }
        }

        return $this;
    }

    /**
     *  Apending filter variables by key
     *
     *  @var mixed $type
     *
     *  @return \Panda\Form\Input
     */
    public function filter($filter, $rule = null)
    {
        if (
            is_scalar($filter) && (
                is_callable($rule) || is_array($rule)
            )
        ) {
            $this->filters[$filter] = $rule;
        } elseif (
            is_scalar($filter) && is_scalar($rule)
        ) {
            $this->filters[$filter] = array($rule);
        } elseif (
            is_scalar($filter) && $rule === null
        ) {
            $this->filters[$filter] = array();
        } elseif (
            is_array($filter)
        ) {
            foreach ($filter as $_filter => $_rule) {
                if (
                    is_numeric($_filter)
                ) {
                    $this->filters[$_rule]      = array();
                } else {
                    $this->filters[$_filter]    = $_rule;
                }
            }
        }

        return $this;
    }

    /**
     *  Get all filter map
     *
     *  @return array
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     *  Сombined method for get of set error message.
     *
     *  @var string $message
     *
     *  @return \Panda\Form\Input
     */
    public function error($message = null)
    {
        if (
            $message === null
        ) {
            return $this->error;
        }

        $this->error = $message;

        return $this;
    }

    /**
     *  HTML rendered method.
     *
     *  @return string
     */
    public function __toString()
    {
        $attr = [];

        foreach (array_replace($this->attr, [
            'name'  => $this->name,
        ]) as $key => $value) {
            array_push(
                $attr, sprintf('%s="%s"', $key, $value)
            );
        }

        if (
            $this->type === static::TEXTAREA
        ) {
            return sprintf(
                '<textarea %s>%s</textarea>', implode(' ', $attr), $this->value
            );
        }

        array_push(
            $attr, sprintf('type="%s"', $this->type)
        );

        if (
            in_array($this->type, array(static::RADIO, static::CHECKBOX), true)
        ) {
            if (
                array_key_exists('value', $this->attr) && $this->attr['value'] == $this->value
            ) {
                array_push($attr, 'checked="checked"');
            }

            return sprintf(
                '<input %s />', implode(' ', $attr)
            );
        }

        array_push(
            $attr, sprintf('value="%s"', $this->value)
        );

        return sprintf(
            '<input %s />', implode(' ', $attr)
        );
    }
}
