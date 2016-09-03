<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.1.0
 */

namespace Panda\Essence;

use Panda\Alloy\FactoryInterface as Factory;

/**
 *  Readable Essence
 *
 *  @subpackage Essence
 */
class Readable extends ReadableAbstract implements Factory
{
    /**
     *  New instance.
     *
     *  @var mixed  $shared
     */
    public function __construct($shared = null)
    {
        if (isset($shared)) {
            $this->shared = is_array($shared) ? $shared : func_get_args();
        }
    }

    /**
     *  Factory instance.
     *
     *  @var mixed  $shared
     */
    public static function factory($shared = null)
    {
        return new static($shared);
    }

    /**
     *  Return whole shared result.
     *
     *  @return array
     */
    public function all()
    {
        return $this->shared;
    }
}
