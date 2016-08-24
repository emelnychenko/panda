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
     *  @var \Panda\Foundation\Support\EssenceReadableInstance
     */
    protected $json     = null;

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return mixed
     */
    public function xhr()
    {
        return $this->server->xmlhttprequest();
    }

    /**
     *  Get json output value or container.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function json($key = null, $default = null)
    {
        if (
            $this->json === null
        ) {
            $bufferize  = @json_decode(file_get_contents('php://input'), true);
        
            $this->json = new EssenceReadableInstance(
                isset($bufferize) ? $bufferize : array()
            );
        }

        if (is_null($key)) {
            return $this->json;
        }


        return $this->json->get($key, $default);
    }
}
