<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

/**
 *  Http Response Interface
 *
 *  @subpackage Http
 */
interface ResponseInterface
{
    /**
     *  Create text/plain response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Http\Response
     */
    public static function text($content = '', $status = 200, array $headers = array());

    /**
     *  Create text/html response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Http\Response
     */
    public static function html($content = '', $status = 200, array $headers = array());

    /**
     *  Create application/json response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Http\Response
     */
    public static function json($content = '', $status = 200, array $headers = array());

    /**
     *  Create application/xml response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Http\Response
     */
    public static function xml($content = '', $status = 200, array $headers = array());

    /**
     *  Create http redirect.
     *
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return Panda\Http\Response
     */
    public static function redirect($url, $status = 303, array $headers = array());

    /**
     *  Comlex status method.
     *
     *  @var numeric $status
     *
     *  @return mixed
     */
    public function status($status = null);

    /**
     *  Comlex content method.
     *
     *  @var string  $content
     *
     *  @return mixed
     */
    public function content($content = null);

    /**
     *  Comlex header method.
     *
     *  @var mixed  $keys
     *  @var mixed  $equal
     *
     *  @return mixed
     */
    public function header($keys = null, $equal = null);

    /**
     *  Renderer response.
     *
     *  @return string
     */
    public function __toString();
}
