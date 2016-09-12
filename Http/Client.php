<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

use Panda\Error\Narrator                as Narrator;
use Panda\Alloy\FactoryInterface        as Factory;
use Panda\Essence\Writeable             as Essence;

/**
 *  Http Client
 *
 *  @subpackage Http
 */
class Client implements Factory
{
    /**
     *  @var string
     */ 
    protected $protocol     = 'http';

    /**
     *  @var string
     */ 
    protected $host         = '127.0.0.1';

    /**
     *  @var string
     */ 
    protected $port         = 80;

    /**
     *  @var string
     */ 
    protected $uri          = '/';

    /**
     *  @var string
     */ 
    protected $url          = '/';

    /**
     *  @var string
     */ 
    protected $hash;

    /**
     *  @var string
     */ 
    protected $method   = 'GET';

    /**
     *  @var string
     */ 
    protected $timeout  = 10;

    /**
     *  @var string
     */ 
    protected $ca;

    /**
     *  @var string
     */ 
    protected $query;

    /**
     *  @var string
     */ 
    protected $header;

    public function __construct($url = null, $port = null)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new Narrator('Url parameter empty of not string');
        }

        $this->query  = Essence::factory();
        $this->header = Essence::factory();

        preg_match('/^(http|https):\/\/([a-z0-9A-Z\-\.]+)(\:[0-9]+|)/i', $url, $matches);

        list($null, $this->protocol, $this->host, $this->port) = $matches;

        $fullpath = str_replace($null, '', $url);

        if ($this->port !== '') {
            strtok($this->port, ':'); $this->port = strtok(':');
        }

        if ($port !== null) $this->port = $port;

        if ($this->port === '') {
            $this->port = $this->protocol === 'https' ? 443 : 80;
        } 

        $this->uri      = ($uri  = strtok($fullpath, '#')) === false ? '/' : $uri;

        $this->hash     = ($hash = strtok('#')) === false ? null : $hash;

        $this->url      = strtok($this->uri, '?');  

        $query          = ($query = strtok('?')) === false ? null : $query;

        if ($query !== null) {
            foreach (explode('&', $query) as $position) {
                $this->query->set(strtok($position, '='), strtok('='));
            }
        }
    }

    public static function factory($url = null, $port = null)
    {
        return new static($url, $port);
    }

    public function query($key, $equal = null)
    {
        $this->query->set($key, $equal);

        return $this;
    }

    public function header($key, $equal = null)
    {
        $this->header->set($key, $equal);

        return $this;
    }

    public function ca($path)
    {
        $this->ca = $path;
        return $this;
    }

    public function timeout($timeout = null)
    {
        if ($timeout === null) {
            return $this->timeout;
        }

        $this->timeout = $timeout;

        return $this;
    }

    public function url()
    {
        $urlize = [
            $this->protocol, '://', $this->host, ':', $this->port, $this->url,
        ];

        $query  = [];

        foreach ($this->query->arrayable() as $key => $value) {
            array_push($query, sprintf('%s=%s', urldecode($key), urldecode($value)));
        }

        if (empty($query) === false) {
            $urlize = array_merge($urlize, [
                '?', implode('&', $query)
            ]);
        }

        if (empty($this->hash) === false) {
            $urlize = array_merge($urlize, [
                '#', $this->hash
            ]);
        }

        return implode('', $urlize);
    }

    public function method($method = null)
    {
        if ($method === null) {
            return $this->method;
        }

        $this->method = $method;

        return $this;
    }

    public function get()
    {
        return $this->send('GET');
    }

    public function send($method = 'GET')
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL             => $this->url(),
            CURLOPT_RETURNTRANSFER  => 1,
            // CURLOPT_HTTPHEADER      => $this->header,
            CURLOPT_CONNECTTIMEOUT  => $this->timeout,
            CURLOPT_TIMEOUT         => $this->timeout,
        ]);

        if ($this->protocol === 'https') {
            curl_setopt_array($curl, [
                CURLOPT_SSL_VERIFYPEER  => true,
                CURLOPT_SSL_VERIFYHOST  => false,
            ]);

            if ($this->ca !== null) {
                curl_setopt_array($curl, [
                    CURLOPT_CAINFO          => $this->ca,
                ]);
            }
        }

        $response   = curl_exec($curl);
        $status     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $errno      = curl_errno($curl);
        $error      = curl_error($curl);
        curl_close($curl);

        return $response;
    }
}
