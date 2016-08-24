<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

use Panda\Foundation\Http\RoutingDispatchAbstract;

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
