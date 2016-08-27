<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ControllerNativeAbstract;

/**
 *  Panda Controller
 *
 *  @subpackage Framework
 */
abstract class Controller extends ControllerNativeAbstract implements ControllerInterface
{
    public function session($container = 'panda.app')
    {
        return new Session($container);
    }

    public function html($content, $status = 200, array $headers = array())
    {
        return Response::html($content, $status, $headers); 
    }

    public function text($content, $status = 200, array $headers = array())
    {
        return Response::text($content, $status, $headers); 
    }

    public function json($content, $status = 200, array $headers = array())
    {
        return Response::json(
            json_encode($content, JSON_PRETTY_PRINT), $status, $headers
        ); 
    }
}

