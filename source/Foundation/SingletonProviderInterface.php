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
interface SingletonProviderInterface
{
    /**
     *  Return singleton instance
     *
     *  @return bool
     */ 
    public static function singleton();
}
