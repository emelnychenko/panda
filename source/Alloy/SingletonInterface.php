<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
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
