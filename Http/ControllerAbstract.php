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

use Frame\Http;
use Frame\Hook;

/**
 *  Http Controller Abstract
 *
 *  @subpackage Http
 */
abstract class ControllerAbstract extends Swimmer implements Factory
{
    use Hook\Mock;

    protected $applique;

    protected $request;

    public function __construct(Applique $app = null)
    {
        $this->request  = Http::request();

        $this->applique = $app;

        $this->hook     = $this->applique->invoke('hook');
    }

    public static function factory(Applique $app = null)
    {
        return new static($app);
    }

    public function app($service = null)
    {
        if ($service !== null) {
            return $this->applique->invoke($service);
        }

        return $this->applique;
    }

    public function invoke($service)
    {
        return $this->app($service);
    }

    public function session($container = 'panda.app')
    {
        return new Session($container);
    }

    /**
     *  Fast router implementation.
     *
     *  @return mixed
     */
    public function request($input = null)
    {
        if ($input === null)
            return $this->request;

        $params = func_get_args();

        return call_user_func_array([
            $this->request, array_shift(
                $params
            )
        ], $params);
    }

    /**
     *  @param  string $input
     *
     *  @return mixed
     */
    public function response($body, $code = 200, $headers = []) {
        /**
         *  @var mixed
         */
        return forward_static_call_array(
            [
                Http::class,
                'response'
            ], func_get_args()
        );
    }

    /**
     *  @param  string      $url
     *  @param  integer     $code
     *  @param  array       $headers
     *
     *  @return mixed
     */
    public function redirect($url, $code = 200, array $headers = [])
    {
        /**
         *  @var \Frame\Response
         */
        return $this->response(
            'redirect', $url, $code, $headers
        );
    }

    /**
     *  @param  string      $url
     *  @param  integer     $code
     *  @param  array       $headers
     *
     *  @return mixed
     */
    public function goto($url, $code = 200, array $headers = [])
    {
        /**
         *  @var \Frame\Response
         */
        return $this->response(
            'redirect', $url, $code, $headers
        );
    }

    /**
     *  @param  string  $path
     *  @param  array   $shared
     *  @param  boolean $prevent
     *  @param  integer $code
     *  @param  array   $headers
     *
     *  @return mixed
     */
    public function view(
        $path,
        array $data   = [],
        $prevent        = false,
        $code         = 200,
        $headers  = []
    ) {
        return $this->response('html',
            $this->invoke('view')->make(
                $path, $data, $prevent
            )->render(), $code, $headers
        );
    }
}
