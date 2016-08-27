<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Provider Singleton Interface
 *
 *  @subpackage Support
 */
interface ProviderSingletonInterface
{
    /**
     *  Return floating desiccion
     *
     *  @return bool
     */ 
    public static function singleton();
}
