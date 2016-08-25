<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

use Panda\Foundation\Http\ClientRequestedAbstract;
use Panda\Foundation\Support\EssenceReadableInstance;

/**
 *  Http Request Instance
 *
 *  @subpackage Http
 */
class Request extends ClientRequestedAbstract implements RequestInterface
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
     *  @return \Panda\Foundation\Http\ClientRequestedAbstract
     */
    public static function factory(
        array $query    = null,
        array $request  = null,
        array $files    = null,
        array $cookie   = null,
        array $server   = null
    ) {
        return new static($query, $request, $files, $cookie, $server);
    }

    /**
     *  Singleton implementation.
     *
     *  @var array $query 
     *  @var array $request
     *  @var array $files
     *  @var array $cookie 
     *  @var array $server
     *
     *  @return \Panda\Foundation\Http\ClientRequestedAbstract
     */
    public static function singleton(
        array $query    = null,
        array $request  = null,
        array $files    = null,
        array $cookie   = null,
        array $server   = null
    ) {
        static $instance = null;

        if (
            is_null($instance)
        ) {
            $instance = new static($query, $request, $files, $cookie, $server);
        }

        return $instance;
    }
}
