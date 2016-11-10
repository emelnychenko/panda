<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form\Data;


use Panda\Alloy\Factory;
use Panda\Form\Validation;
use Panda\Form\Sanitation;

/**
 *  Panda Form Data
 *
 *  @subpackage Framework
 */
class Input implements Factory
{
    /**
     *  @var string
     */
    protected $equal    = null;

    /**
     *  @var string
     */
    protected $filter   = [];

    /**
     *  @var string
     */
    protected $valid    = true;

    /**
     *  @var string
     */
    protected $error    = [];

    /**
     *  @var string
     */
    protected $report    = [];

    /**
     *  Get input value
     *
     *  @return scalar
     */
    public function get()
    {
        return $this->equal;
    }

    /**
     *  Get readonly values.
     *
     *  @param  scalar
     *
     *  @return mixed
     */
    public function __get($key)
    {
        return $this->{$key};
    }

    /**
     *  Set input value.
     *
     *  @param scalar
     */
    public function set($equal)
    {
        $this->equal = $equal;

        return $this;
    }

    /**
     *  @param scalar
     */
    public function __construct($equal = null)
    {
        $this->equal = $equal;
    }

    /**
     *  @param scalar
     *
     *  @return \Panda\Form\Data\Input
     */
    public static function factory($equal = null)
    {
        return new static($equal);
    }

    /**
     *  Sanitize whole filters stack and set equal.
     *
     *  @return \Panda\Form\Data\Input
     */
    public function sanitize()
    {
        foreach ($this->filter as $filter => $mixed) {
            if (method_exists(Validation::class, $filter))  {
                array_unshift($mixed, $this->equal);

                $this->equal = forward_static_call_array(
                    [Sanitation::class, $filter], $mixed
                );
            }
        }

        return $this;
    }

    /**
     *  Validate whole filters stack and write report.
     *
     *  @return \Panda\Form\Data\Input
     */
    public function validate()
    {
        $this->valid = $valid = true;

        foreach ($this->filter as $filter => $mixed) {
            if (method_exists(Validation::class, $filter))  {
                array_unshift($mixed, $this->equal);

                $this->report[$filter] = $valid = forward_static_call_array(
                    [Validation::class, $filter], $mixed
                );
            }

            if ($valid === false) $this->valid = false;
        }

        return $this;
    }

    /**
     *  Update filters stack with associative names.
     *
     *  @param  mixed $filter
     *  @param  mixed $helper
     *
     *  @return \Panda\Form\Data\Input
     */
    public function filter($filter, $helper = null)
    {
        $filters = is_array($filter) ? $filter : (
            $helper === null ? [$filter => []] : [$filter => $helper]
        );

        foreach ($filters as $filter => $helper) {
            if (is_numeric($filter)) {
                $this->filter[$helper] = [];
            } else {
                $this->filter[$filter] = is_array($helper) ? $helper : [$helper];
            }
        }

        return $this;
    }

    /**
     *  @return bool
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     *  @param  mixed $filter
     *  @param  mixed $message
     *
     *  @return \Panda\Form\Data\Input
     */
    public function error($filter = null, $message = null)
    {
        if ($filter === null) {
            foreach ($this->report as $filter => $dessicion) {
                if ($dessicion === false) {
                    return isset($this->error[$filter]) ? $this->error[$filter] : (
                        isset($this->error['equal']) ? $this->error['equal'] : null
                    );
                }
            }

            return null;
        }

        $filtes = is_array($filter) ? $filter : (
            $message === null ? [$filter] : [$filter => $message]
        );

        foreach ($filtes as $filter => $message) {
            $this->error[is_numeric($filter) ? 'equal' : $filter] = $message;
        }

        return $this;
    }
}
