<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

/**
 *  Client Request Processor
 *
 *  @subpackage Http
 */
interface RequestInterface
{
    public function json($key = null, $default = null);
    public function xhr();
}
