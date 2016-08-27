<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ClientRequestedAbstract;

use Panda\Foundation\SingletonProviderInterface;
use Panda\Foundation\SingletonProviderExpansion;

/**
 *  Panda Request
 *
 *  @subpackage Framework
 */
class Request extends ClientRequestedAbstract implements RequestInterface, SingletonProviderInterface
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
     *  @return Panda\Request
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

    use SingletonProviderExpansion;
}
