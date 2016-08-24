<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http;

use Panda\Foundation\Http\ResponseImplementation\EssenceHeaders;

/**
 *  Client Response Abstract
 *
 *  @subpackage Http
 */
abstract class ClientResponseAbstract implements ClientResponseInterface
{
    const STATUS_CODE_CUSTOM = 0;
    const STATUS_CODE_100 = 100;
    const STATUS_CODE_101 = 101;
    const STATUS_CODE_102 = 102;
    const STATUS_CODE_200 = 200;
    const STATUS_CODE_201 = 201;
    const STATUS_CODE_202 = 202;
    const STATUS_CODE_203 = 203;
    const STATUS_CODE_204 = 204;
    const STATUS_CODE_205 = 205;
    const STATUS_CODE_206 = 206;
    const STATUS_CODE_207 = 207;
    const STATUS_CODE_208 = 208;
    const STATUS_CODE_300 = 300;
    const STATUS_CODE_301 = 301;
    const STATUS_CODE_302 = 302;
    const STATUS_CODE_303 = 303;
    const STATUS_CODE_304 = 304;
    const STATUS_CODE_305 = 305;
    const STATUS_CODE_306 = 306;
    const STATUS_CODE_307 = 307;
    const STATUS_CODE_400 = 400;
    const STATUS_CODE_401 = 401;
    const STATUS_CODE_402 = 402;
    const STATUS_CODE_403 = 403;
    const STATUS_CODE_404 = 404;
    const STATUS_CODE_405 = 405;
    const STATUS_CODE_406 = 406;
    const STATUS_CODE_407 = 407;
    const STATUS_CODE_408 = 408;
    const STATUS_CODE_409 = 409;
    const STATUS_CODE_410 = 410;
    const STATUS_CODE_411 = 411;
    const STATUS_CODE_412 = 412;
    const STATUS_CODE_413 = 413;
    const STATUS_CODE_414 = 414;
    const STATUS_CODE_415 = 415;
    const STATUS_CODE_416 = 416;
    const STATUS_CODE_417 = 417;
    const STATUS_CODE_418 = 418;
    const STATUS_CODE_422 = 422;
    const STATUS_CODE_423 = 423;
    const STATUS_CODE_424 = 424;
    const STATUS_CODE_425 = 425;
    const STATUS_CODE_426 = 426;
    const STATUS_CODE_428 = 428;
    const STATUS_CODE_429 = 429;
    const STATUS_CODE_431 = 431;
    const STATUS_CODE_500 = 500;
    const STATUS_CODE_501 = 501;
    const STATUS_CODE_502 = 502;
    const STATUS_CODE_503 = 503;
    const STATUS_CODE_504 = 504;
    const STATUS_CODE_505 = 505;
    const STATUS_CODE_506 = 506;
    const STATUS_CODE_507 = 507;
    const STATUS_CODE_508 = 508;
    const STATUS_CODE_511 = 511;

    /**
     *  @var array
     */
    protected $messages = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    );

    /**
     *  @var integer
     */
    protected $status   = 200;

    /**
     *  @var array
     */
    protected $headers  = null;

    /**
     *  @var string
     */
    protected $content  = '';

    /**
     *  Create new responce instance.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     */
    public function __construct($content = '', $status = 200, $headers = array())
    {
        $this->content($content); $this->status($status);
    
        $this->headers = new EssenceHeaders($headers);
    }

    /**
     *  Factory method.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return \Panda\Foundation\Http\ClientResponseAbstract
     */
    public static function create($content = '', $status = 200, $headers = array())
    {
        return new static($content, $status, $headers);
    } 

    /**
     *  Get status, set status code.
     *
     *  @var mixed  $status
     *
     *  @return mixed
     */
    public function status($status = null)
    {
        if (
            $status === null
        ) {
            return $this->status;
        } elseif (
            is_numeric($status)
        ) {
            $this->status = $status;
        }

        return $this;
    }

    /**
     *  Get content, set content.
     *
     *  @var mixed  $status
     *
     *  @return mixed
     */
    public function content($content = null)
    {
        if (
            $content === null
        ) {
            return $this->content;
        } elseif (
            is_string($content)
        ) {
            $this->content = $content;
        }

        return $this;
    }

    /**
     *  Get headers Essence, value or set header.
     *
     *  @var mixed  $key
     *  @var mixed  $equal
     *
     *  @return mixed
     */
    public function header($key = null, $equal = null)
    {
        if (
            is_null($key) && is_null($equal)
        ) {
            return $this->headers;
        } elseif (
            is_string($key) && is_null($equal)
        ) {
            return $this->headers->get($key);
        } else if (
            is_string($key) && is_string($equal)
        ) {
            $this->headers->set($key, $equal);
        }

        return $this;
    }

    /**
     *  Render responce components.
     *
     *  @return string
     */
    public function render()
    {
        foreach ($this->headers->all() as $header_key => $header_equal) {
            header(sprintf('%s: %s', $header_key, $header_equal));
        }

        http_response_code($this->status);

        return $this->content;
    }

    /**
     *  Send to client content.
     *
     *  @return null
     */
    public function send()
    {
        echo $this->render();

        return;
    }

    /**
     *  Render response instance.
     */
    public function __toString()
    {
        return $this->render();
    }
}
