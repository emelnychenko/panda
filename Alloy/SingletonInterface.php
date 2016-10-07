<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Alloy;

/**
 *  Singleton Alloy
 *
 *  @subpackage Alloy
 */
interface SingletonInterface
{
    /**
     *  Singleton instance.
     *
     *  @return Factory
     */
    public static function singleton();
}
