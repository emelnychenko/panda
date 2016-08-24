<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

use Panda\Foundation\Http\ClientResponseAbstract;

/**
 *  Http Response Instance
 *
 *  @subpackage Http
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
     *  @return \Panda\Http\Response
     */
    public static function text($content = '', $status = 200, $headers = array())
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
     *  @return \Panda\Http\Response
     */
    public static function html($content = '', $status = 200, $headers = array())
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
     *  @return \Panda\Http\Response
     */
    public static function json($content = '', $status = 200, $headers = array())
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
     *  @return \Panda\Http\Response
     */
    public static function xml($content = '', $status = 200, $headers = array())
    {
        return static::create(
            $content, $status, array_replace($headers, array('Content-Type' => 'application/xml; charset=utf-8'))
        );
    }
}
