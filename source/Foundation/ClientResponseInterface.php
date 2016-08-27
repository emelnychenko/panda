<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Client Response Interface
 *
 *  @subpackage Foundtion
 */
interface ClientResponseInterface
{
    /**
     *  Factory method.
     *
     *  @var string  $content
     *  @var integer $status
     *  @var array   $headers
     *
     *  @return mixed
     */
    public static function create($content = '', $status = 200, array $headers = array());

    /**
     *  Get status, set status code.
     *
     *  @var mixed  $status
     *
     *  @return mixed
     */
    public function status($status = null);

    /**
     *  Get content, set content.
     *
     *  @var mixed  $status
     *
     *  @return mixed
     */
    public function content($content = null);

    /**
     *  Get headers Essence, value or set header.
     *
     *  @var mixed  $key
     *  @var mixed  $equal
     *
     *  @return mixed
     */
    public function header($key = null, $equal = null);

    /**
     *  Render responce components.
     *
     *  @return string
     */
     public function render();

    /**
     *  Send to client content.
     *
     *  @return null
     */
    public function send();

    /**
     *  Render response instance.
     */
    public function __toString();
}
