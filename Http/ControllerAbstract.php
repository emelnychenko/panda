<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

use Panda\Deploy\Applique               as Applique;
use Panda\Essence\SwimmerAbstract       as Swimmer;
use Panda\Alloy\FactoryInterface        as Factory;
use Panda\Http\Session                  as Session;
use Panda\Http\Cookie                   as Cookie;
use Panda\Swift\View                    as View;

/**
 *  Http Controller Abstract
 *
 *  @subpackage Http
 */
abstract class ControllerAbstract extends Swimmer implements Factory
{
    protected $applique;

    public function __construct(Applique $applique = null)
    {
        $this->applique = $applique;
    }

    public static function factory(Applique $applique = null)
    {
        return new static($applique);
    }

    public function request()
    {
        return $this->applique->router()->request();
    }

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

    public function view(
        $path, 
        array $shared       = [], 
        $prevent            = false, 
        $status             = 200, 
        array $headers      = []
    ) {
        return $this->html(
            $this->applique->view()->compile(
                $path, $shared, $prevent
            )->render(), $status, $headers
        );
    }

    public function redirect($url, $status = 303, array $headers = array())
    {
        return Response::redirect($url, $status, $headers);
    }
}
