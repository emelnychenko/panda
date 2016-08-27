<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ClientRequestedAbstract;
use Panda\Foundation\ProviderSingletonInterface;

/**
 *  Http Request Instance
 *
 *  @subpackage Http
 */
class Request extends ClientRequestedAbstract implements RequestInterface, ProviderSingletonInterface
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
     *  @return \Panda\Http\Request
     */
    public static function singleton() {
        static $instance = null;

        if (
            is_null($instance)
        ) {
            $instance = new static();
        }

        return $instance;
    }
}
