<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.1.0
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
