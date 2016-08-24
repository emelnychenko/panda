<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http;

use Panda\Foundation\Http\RequestImplementation\EssenceFiles;
use Panda\Foundation\Http\RequestImplementation\EssenceCookie;
use Panda\Foundation\Http\RequestImplementation\EssenceServer;
use Panda\Foundation\Support\EssenceReadableInstance;

/**
 *  Client Request
 *
 *  @subpackage Http
 */
abstract class ClientRequestedAbstract implements ClientRequestedInterface
{
    /**
     *  @var string
     */
    protected $method   = null;

    /**
     *  @var string
     */
    protected $locale   = null;

    /**
     *  @var \Panda\Foundation\Support\EssenceReadableInstance
     */
    public $query       = null;

    /**
     *  @var \Panda\Foundation\Support\EssenceReadableInstance
     */
    public $request     = null;

    /**
     *  @var \Panda\Foundation\Http\RequestImplementation\EssenceFiles
     */
    public $files       = null;

    /**
     *  @var \Panda\Foundation\Http\RequestImplementation\EssenceCookie
     */
    public $cookie      = null;

    /**
     *  @var \Panda\Foundation\Http\RequestImplementation\EssenceServer
     */
    public $server      = null;

    /**
     *  Simple constructor.
     *
     *  @var array $query 
     *  @var array $request
     *  @var array $files
     *  @var array $cookie 
     *  @var array $server
     */
    public function __construct(
        array $query    = null,
        array $request  = null,
        array $files    = null,
        array $cookie   = null,
        array $server   = null
    ) {
        $query          = isset($query)   ? $query   : $_GET;
        $request        = isset($request) ? $request : $_POST;
        $files          = isset($files)   ? $files   : $_FILES;
        $cookie         = isset($cookie)  ? $cookie  : $_COOKIE;
        $server         = isset($server)  ? $server  : $_SERVER;

        $this->query    = new EssenceReadableInstance($query);
        $this->request  = new EssenceReadableInstance($request);
        $this->files    = new EssenceFiles($files);
        $this->cookie   = new EssenceCookie($cookie);
        $this->server   = new EssenceServer(
            array_replace(array(
                'SERVER_NAME'           => 'localhost',
                'SERVER_PORT'           => 80,
                'HTTP_HOST'             => 'localhost',
                'HTTP_USER_AGENT'       => 'Panda/1.x',
                'HTTP_ACCEPT'           => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'HTTP_ACCEPT_LANGUAGE'  => 'en-us,en;q=0.5',
                'HTTP_ACCEPT_CHARSET'   => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                'REMOTE_ADDR'           => '127.0.0.1',
                'SCRIPT_NAME'           => '',
                'SCRIPT_FILENAME'       => '',
                'SERVER_PROTOCOL'       => 'HTTP/1.1',
                'REQUEST_TIME'          => time(),
            ), $server)
        );
    }

    /**
     *  Get uri path.
     *
     *  @return string
     */
    public function uri()
    {
        return $this->server->uri();
    }

    /**
     *  Get url path (without uri).
     *
     *  @return string
     */
    public function url()
    {
        return $this->server->url();
    }

    /**
     *  Get client IP.
     *
     *  @return string
     */
    public function ip()
    {
        return $this->server->ip();
    }

    /**
     *  Get client user agent.
     *
     *  @return string
     */
    public function agent()
    {
        return $this->server->agent();
    }

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function domain()
    {
        return $this->server->domain();
    }

    /**
     *  Get document root.
     *
     *  @return string
     */
    public function docroot()
    {
        return $this->server->docroot();
    }

    /**
     *  Get request method or verify if method is $question.
     *
     *  @var mixed $question
     *
     *  @return mixed
     */
    public function method($question = null)
    {
        if (
            $this->method === null
        ) {
            $this->method = $this->server->method('GET');
        }

        if (
            is_string($question)
        ) {
            return strtoupper($question) === $this->method;
        }

        return $this->method;
    }

    /**
     *  Get client locale or verify if locale is $question.
     *
     *  @var mixed $question
     *
     *  @return mixed
     */
    public function locale($question = null)
    {
        if (
            $this->locale === null
        ) {
            $this->locale = $this->server->locale('en');
        }

        if (
            is_string($question)
        ) {
            return strtoupper($question) === $this->locale;
        }

        return $this->locale;
    }

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return bool
     */
    public function ajax()
    {
        return $this->server->xmlhttprequest();
    }
}
