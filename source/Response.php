<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ClientResponseAbstract;

/**
 *  Panda Response
 *
 *  @subpackage Framework
 */
class Response extends ClientResponseAbstract implements ResponseInterface
{
    /**
     *  Create text/plain response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Response
     */
    public static function text($content = '', $status = 200, array $headers = array())
    {
        return static::create(
            $content, $status, array_replace($headers, array('Content-Type' => 'text/plain; charset=utf-8'))
        );
    }

    /**
     *  Create text/html response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Response
     */
    public static function html($content = '', $status = 200, array $headers = array())
    {
        return static::create(
            $content, $status, array_replace($headers, array('Content-Type' => 'text/html; charset=utf-8'))
        );
    }

    /**
     *  Create application/json response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Response
     */
    public static function json($content = '', $status = 200, array $headers = array())
    {
        return static::create(
            $content, $status, array_replace($headers, array('Content-Type' => 'application/json; charset=utf-8'))
        );
    }

    /**
     *  Create application/xml response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Response
     */
    public static function xml($content = '', $status = 200, array $headers = array())
    {
        return static::create(
            $content, $status, array_replace($headers, array('Content-Type' => 'application/xml; charset=utf-8'))
        );
    }

    /**
     *  Create http redirect.
     *
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Response
     */
    public static function redirect($url, $status = 303, array $headers = array())
    {
        return static::create(
            '', $status, array_replace($headers, array('Location' => $url))
        );
    }
}
