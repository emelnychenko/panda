<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Provider Singleton Interface
 *
 *  @subpackage Foundation
 */
trait SingletonProviderExpansion
{
    /**
     *  Singleton layer.
     *
     *  @return \Panda\Http\Router
     */
    public static function singleton()
    {
        static $instance = null;

        if (
            is_null($instance)
        ) {
            $instance = new static();
        }

        return $instance;
    }
}
