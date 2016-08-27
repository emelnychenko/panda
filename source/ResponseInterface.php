<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

/**
 *  Panda Response
 *
 *  @subpackage Framework
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
