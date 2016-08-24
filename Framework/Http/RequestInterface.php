<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Framework
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

/**
 *  Http Request Interface
 *
 *  @subpackage Http
 */
interface RequestInterface
{
    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return mixed
     */
    public function xhr();

    /**
     *  Get json output value or container.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function json($key = null, $default = null);
}
