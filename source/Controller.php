<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ControllerNativeAbstract;

/**
 *  Http Controller Abstract
 *
 *  @subpackage *
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
        if (
            version_compare(PHP_VERSION, '5.4.0') >= 0
        ) {
            return Response::json(
                json_encode($content, JSON_PRETTY_PRINT), $status, $headers
            ); 
        }

        return Response::json(
            json_encode($content), $status, $headers
        ); 
    }
}

