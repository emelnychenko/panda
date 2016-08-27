<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

namespace Panda;

/**
 *  Http Request Interface
 *
 *  @subpackage Http
 */
interface RequestInterface
{
    /**
     *  Factory implementation.
     *
     *  @var array $query 
     *  @var array $request
     *  @var array $files
     *  @var array $cookie 
     *  @var array $server
     *
     *  @return \Panda\Http\Request
     */
    public static function factory(
        array $query    = null,
        array $request  = null,
        array $files    = null,
        array $cookie   = null,
        array $server   = null
    );
}
