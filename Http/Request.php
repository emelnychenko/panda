<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

use Panda\Http\Request\File             as File;
use Panda\Alloy\FactoryInterface        as Factory;
use Panda\Essence\Readable              as Essence;

/**
 *  Http Request
 *
 *  @subpackage Http
 */
class Request extends Essence implements RequestInterface, Factory
{
    /**
     *  @var \Panda\Essence\Readable
     */
    public $query       = null;

    /**
     *  @var \Panda\Essence\Readable
     */
    public $request     = null;

    /**
     *  @var \Panda\Essence\Readable
     */
    public $files       = null;

    /**
     *  @var \Panda\Essence\Readable
     */
    public $cookie      = null;

    /**
     *  @var \Panda\Essence\Readable
     */
    public $server      = null;

    /**
     *  @var array
     */
    public $shared      = [];

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
        $this->query    = Essence::factory($query  !== null ? $query  : $_GET);
        $this->files    = Essence::factory($files  !== null ? $files  : $_FILES);
        $this->cookie   = Essence::factory($cookie !== null ? $cookie : $_COOKIE);
        $this->server   = Essence::factory(
            array_replace([
                'SERVER_NAME'           => 'localhost',
                'SERVER_PORT'           => '80',
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
                'REQUEST_URI'           => '/',
            ], $server !== null ? $server : $_SERVER)
        );

        if ($request !== null) {
            $this->request  = Essence::factory($request);
        } else {
            $input          = file_get_contents('php://input');
            $decoded        = json_decode($input, true);

            $this->request = Essence::factory(
                $this->json() && json_last_error() === 0 ? $decoded : $_POST
            );
        }

        parent::__construct(
            $this->method() === 'GET' ? $this->query->arrayable() : $this->request->arrayable()
        );
    }

    /**
     *  Simple constructor.
     *
     *  @var array $query 
     *  @var array $request
     *  @var array $files
     *  @var array $cookie 
     *  @var array $server
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
     *  Get query essence, query param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function query($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }

        return $this->query->get($key, $default);
    }

    /**
     *  Get request essence, request param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function request($key = null, $default = null)
    {
        if ($key === null) {
            return $this->request;
        }

        return $this->request->get($key, $default);
    }

    /**
     *  Get cookie essence, cookie layer model.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function cookie($key = null, $default = null)
    {
        if ($key === null) {
            return $this->cookie;
        }

        return Cookie::factory(
            $key, $this->cookie->get($key, $default)
        );
    }

    /**
     *  Get session layer model
     *
     *  @var mixed $key
     *
     *  @return \Panda\Http\Session
     */
    public function session($key = null)
    {
        return Session::factory($key);
    }

    /**
     *  Get files essence, files param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function file($key, $default = null)
    {
        return File::factory(
            $this->files->get($key, [])
        );
    }

    /**
     *  Get files essence, files param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function files($key = null, $default = null)
    {
        return $this->files;
    }

    /**
     *  Get server essence, server param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function server($key = null, $default = null)
    {
        if ($key === null) {
            return $this->server;
        }

        return $this->server->get($key, $default);
    }

    /**
     *  Verify if request is json content type.
     *
     *  @return bool
     */
    public function json()
    {
        return (bool) preg_match(
            '/(text|application)\/json/i', $this->server('CONTENT_TYPE')
        );
    }

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return bool
     */
    public function xhr()
    {
        return 'xmlhttprequest' == strtolower(
            $this->server('HTTP_X_REQUESTED_WITH')
        );
    }

    /**
     *  Get uri path.
     *
     *  @return string
     */
    public function uri()
    {
        return $this->server('REQUEST_URI');
    }

    /**
     *  Get url path (without uri).
     *
     *  @return string
     */
    public function path()
    {
        return strtok($this->uri(), '?');
    }

    /**
     *  Get url path (without uri).
     *
     *  @return string
     */
    public function url($replace = null)
    {
        return sprintf(
            "%s%s", $this->domain(), $replace !== null ? $replace : $this->path()
        );
    }

    /**
     *  Compare url path.
     *
     *  @return bool
     */
    public function is($url = '/')
    {
        $windcard = str_replace(['*', '/'], ['.*?', '\/'], $url);

        return (bool) preg_match(
            sprintf('/^%s$/i', $windcard), $this->url()
        );
    }

    /**
     *  Get client IP.
     *
     *  @return string
     */
    public function ip()
    {
        return $this->server('REMOTE_ADDR', '127.0.0.1');
    }

    /**
     *  Get client user agent.
     *
     *  @return string
     */
    public function agent()
    {
        return $this->server('HTTP_USER_AGENT');
    }

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function protocol()
    {
        return $this->server('HTTPS', 'off') !== 'off' ? 'https' : 'http';
    }

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function host()
    {
        return $this->server('HTTP_HOST', 'localhost');
    }

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function port()
    {
        return $this->server('SERVER_PORT', '80');
    }

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function domain()
    {
        return sprintf(
            '%s://%s', $this->protocol(), $this->host()
        );
    }

    /**
     *  Get document root.
     *
     *  @return string
     */
    public function rundir()
    {
        return $this->server('DOCUMENT_ROOT');
    }

    /**
     *  Get request method or verify if method is $question.
     *
     *  @return mixed
     */
    public function method($middleware = null, $param = null)
    {
        if ($middleware === null) {
            return $this->server('REQUEST_METHOD', 'GET');
        }

        return call_user_func([
            $this, sprintf("%s_%s", __FUNCTION__, $middleware)
        ], $param);
    }

    /**
     *  Get request method or verify if method is $question.
     *
     *  @return mixed
     */
    public function method_is($method)
    {
        return strtoupper($method) === $this->method();
    }

    /**
     *  Get client locale or verify if locale is $question.
     *
     *  @var mixed $question
     *
     *  @return mixed
     */
    public function locale($locale = 'en')
    {
        if (
            preg_match_all(
                '/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', 
                strtolower(
                    $this->server('HTTP_ACCEPT_LANGUAGE')
                ),
                $matches
            )
        ) {
            $language = array();

            foreach (array_combine($matches[1], $matches[2]) as $key => $value) {
                $language[$key] = empty($value) ? 1 : (float) $value;
            }

            arsort($language, SORT_NUMERIC);

            $language   = array_keys($language);
            $primary    = array_shift(
                $language
            );

            $locale = strtok($primary, '-');
        }

        return $locale;
    }
}
