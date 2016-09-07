<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.3.0
 */

namespace Panda\Console;

use Panda\Essence\Writeable         as Essence;
use Panda\Alloy\FactoryInterface    as Factory;

/**
 *  Readable Essence
 *
 *  @subpackage Essence
 */
class Bamboo implements Factory
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

    public function run()
    {
        return 'Hello Panda Bamboo.' . PHP_EOL;
    }
}
