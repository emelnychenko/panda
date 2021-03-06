<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Essence;

use Panda\Alloy\FactoryInterface as Factory;

/**
 *  Writeable Essence
 *
 *  @subpackage Essence
 */
class Writeable extends WriteableAbstract implements Factory
{
    /**
     *  New instance.
     *
     *  @var mixed  $shared
     */
    public function __construct($shared = null)
    {
        $this->set($shared);
        // $this->shared = $shared;
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
        return $this->shared();
    }
}
