<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

use Panda\Foundation\Http\ClientRequestedAbstract;
use Panda\Foundation\Support\EssenceReadableInstance;

/**
 *  Client Request Processor
 *
 *  @subpackage Http
 */
class Request extends ClientRequestedAbstract implements RequestInterface
{
    protected $json     = null;

    public function xhr()
    {
        return $this->server->xmlhttprequest();
    }

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

