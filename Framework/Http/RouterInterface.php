<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

/**
 *  Http Router Interface
 *
 *  @subpackage Http
 */
interface RouterInterface
{
    /**
     *  Factory layer.
     *
     *  @var \Panda\Http\Request $request
     *
     *  @return \Panda\Http\Router
     */
    public static function factory(Request $request);

    /**
     *  Singleton layer.
     *
     *  @var \Panda\Http\Request $request
     *
     *  @return \Panda\Http\Router
     */
    public static function singleton(Request $request = null);
}
