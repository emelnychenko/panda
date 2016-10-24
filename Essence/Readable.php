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
 *  Readable Essence 
 *
 *  @subpackage Essence
 */
class Readable extends ReadableAbstract implements Factory
{
    /**
     *  New Instance
     * 
     *  @param mixed $shared 
     */
    public function __construct($shared = null)
    {
        $this->shared = $shared;
    }

    /**
     *  Factory instance.
     *
     *  @param mixed $shared 
     */
    public static function factory($shared = null)
    {
        return new static($shared);
    }
}
