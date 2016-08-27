<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

use Panda\Foundation\Support\EssenceReadableInstance;

/**
 *  Client Request
 *
 *  @subpackage Foundation
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
     *  @var \Panda\Foundation\Support\EssenceReadableInstance
     */
    protected $json     = null;

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
        $this->files    = new EssenceReadableInstance($files);
        $this->cookie   = new EssenceReadableInstance($cookie);
        $this->server   = new EssenceReadableInstance(
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
     *  Get main essence.
     *
     *  @return @var \Panda\Foundation\Support\EssenceReadableInstance
     */
    public function source()
    {
        if (
            $this->is_json()
        ) {
            return $this->json();
        }

        return $this->method() === 'GET' ? $this->query : $this->request;
    }

    /**
     *  Get only array keys from main essence.
     *
     *  @var mixed $key
     *
     *  @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return $this->source()->only($keys);
    }

    /**
     *  Get actual array dataset from main essence.
     *
     *  @return array
     */
    public function all()
    {
        return $this->source()->all();
    }

    /**
     *  Get request essence, request param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function input($key = null, $default = null)
    {
        if (
            $key === null
        ) {
            return $this->source();
        }

        return $this->source()->get($key, $default);
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
     *  Get cookie essence, cookie param.
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

        return $this->cookie->get($key, $default);
    }

    /**
     *  Get files essence, files param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function file($key = null, $default = null)
    {
        if ($key === null) {
            return $this->files;
        }

        return $this->files->get($key, $default);
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

    /**
     *  Verify if request is json content type.
     *
     *  @return bool
     */
    public function is_json()
    {
        return preg_match(
            '/(text|application)\/json/i', $this->server('CONTENT_TYPE')
        );
    }

    /**
     *  Compare url path.
     *
     *  @return bool
     */
    public function is($url = '/')
    {
        $windcard = str_replace(
            array('*', '/'), array('.*?', '\/'), $url
        );

        return (bool) preg_match(
            sprintf('/^%s$/i', $windcard), $this->url()
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
    public function url()
    {
        return strtok($this->uri(), '?');
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
    public function domain()
    {
        return $this->server('HTTP_HOST');
    }

    /**
     *  Get document root.
     *
     *  @return string
     */
    public function docroot()
    {
        return $this->server('DOCUMENT_ROOT');
    }

    /**
     *  Get request method or verify if method is $question.
     *
     *  @return mixed
     */
    public function method()
    {
        if (
            $this->method === null
        ) {
            $this->method = $this->server('REQUEST_METHOD', 'GET');
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
    public function locale($locale = 'en')
    {
        if (
            $this->locale === null
        ) {
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

            $this->locale = $locale; 
        }
        
        return $this->locale;
    }

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return mixed
     */
    public function xhr()
    {
        return 'xmlhttprequest' == strtolower(
            $this->server('HTTP_X_REQUESTED_WITH')
        );
    }

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return bool
     */
    public function ajax()
    {
        return $this->xhr();
    }
}
