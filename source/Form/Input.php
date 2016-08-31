<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

use Panda\Form\Sanitation;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Input
{
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
     *  @var bool
     */ 
    protected $null         = false;

    /**
     *  @var string
     */ 
    protected $value        = null;

    /**
     *  @var string
     */ 
    protected $filter       = array();

    /**
     *  @var string
     */ 
    protected $validated    = array();

    /**
     *  @var string
     */ 
    protected $valid        = true;

    /**
     *  @var string
     */ 
    protected $error        = null;

    public function __construct($type, $name, array $attr = array(), $value = null)
    {
        $this->type     = $type; 
        $this->name     = $name;
        $this->attr     = $attr;

        $this->set($value);
    }

    public static function factory($type, $name, array $attr = array(), $value = null)
    {
        return new static($type, $name, $attr, $value);
    }

    public function attr($attr = null, $equal = null)
    {
        if ($attr === null && $equal === null) {
            return $this->attr;
        }

        panda_iterator($attr, $equal, function($attr, $equal) {
            $this->attr[$attr] = $equal;
        });

        return $this;
    }

    public function type($type)
    {
        if ($name === null) {
            return $this->type;
        }

        $this->type = $type;

        return $this;
    }

    public function name($name = null)
    {
        if ($name === null) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    public function get()
    {
        return $this->value;
    }

    public function set($value)
    {
        $this->value = htmlspecialchars(
            stripslashes(
                trim($value)
            )
        );

        return $this;
    }

    public function disabled($desiccion = true)
    {
        if (
            $desiccion === true
        ) {
            $this->attr['disabled'] = 'disabled';
        } elseif (
            array_key_exists('disabled', $this->attr)
        ) {
            unset($this->attr['disabled']);
        }

        return $this;
    }

    public function disable()
    {
        return $this->disabled(true);
    }

    public function required($desiccion = true)
    {
        if (
            $desiccion === true
        ) {
            $this->attr['required']     = 'required';
            $this->filter['required']   = array();
        } else {
            if (
                array_key_exists('required', $this->attr)
            ) {
                unset($this->attr['required']);
            }

            if (
                array_key_exists('required', $this->filter)
            ) {
                unset($this->filter['required']);
            }
        }

        return $this;
    }

    // public function require()
    // {
    //     return $this->required(true);
    // }

    public function validate()
    {
        $this->valid = true;

        foreach ($this->filter as $filter => $argument) {
            if (
                method_exists(Validation::class, $filter)
            ) {
                $argument = is_array($argument) ? $argument : [$argument];

                array_unshift($argument, $this->value);

                $this->validated[$filter] = $valid = forward_static_call_array([
                    Validation::class, $filter 
                ], $argument);

                if ($valid === false) {
                    $this->valid = false;
                }
            }
        }

        return $this;
    }


    public function valid()
    {
        return $this->valid;
    }

    public function sanitize()
    {
        foreach ($this->filter as $filter => $argument) {
            if (
                method_exists(Sanitation::class, $filter)
            ) {
                $argument = is_array($argument) ? $argument : [$argument];

                array_unshift($argument, $this->value);

                $this->value = forward_static_call_array([
                    Sanitation::class, $filter 
                ], $argument);
            }
        }

        return $this;
    }

    public function filter($equal)
    {
        panda_iterator($equal, null, function($filter, $equal) {
            if (is_numeric($filter)) {
                $this->filter[$equal] = array();
                $this->__sync($equal);
            } else {
                $this->filter[$filter] = $equal;
                $this->__sync($filter, $equal);
            }
        });

        return $this;
    }

    protected function __sync($filter, $value = null)
    {
        if (
            $filter === 'max'
        ) {
            $this->attr['maxlength'] = $value;
        } elseif (
            $filter === 'min'
        ) {
            $this->attr['minlength'] = $value;
        } elseif (
            $filter === 'between'
        ) {
            list($this->attr['minlength'], $this->attr['maxlength']) = $value;
        } elseif (
            $filter === 'require'
        ) {
            $this->require();
        }
    }

    public function filters()
    {
        return $this->filter;
    }

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

    public function __toString()
    {
        $attr = [];

        foreach (array_replace($this->attr, [
            'type'  => $this->type,
            'name'  => $this->name,
            'value' => $this->value,
        ]) as $key => $value) {
            array_push(
                $attr, sprintf('%s="%s"', $key, $value)
            );
        }

        return sprintf(
            '<input %s />', implode(' ', $attr)
        );
    }
}
