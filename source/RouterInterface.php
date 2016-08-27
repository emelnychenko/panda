<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

/**
 *  Panda Router
 *
 *  @subpackage Framework
 */
interface RouterInterface
{
    /**
     *  Factory layer.
     *
     *  @var Panda\Request $request
     *
     *  @return Panda\Router
     */
    public static function factory(Request $request);
}
