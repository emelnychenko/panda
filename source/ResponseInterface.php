<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

namespace Panda;

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
     *  @return \Panda\Http\Response
     */
    public static function text($content = '', $status = 200, array $headers = array());

    /**
     *  Create text/html response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return \Panda\Http\Response
     */
    public static function html($content = '', $status = 200, array $headers = array());

    /**
     *  Create application/json response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return \Panda\Http\Response
     */
    public static function json($content = '', $status = 200, array $headers = array());

    /**
     *  Create application/xml response.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return \Panda\Http\Response
     */
    public static function xml($content = '', $status = 200, array $headers = array());
}
