<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.1.0
 */

namespace Panda\Http;

use Panda\Alloy\FactoryInterface        as Factory;
use Panda\Alloy\StorageInterface        as Storage;
use Panda\Essence\WriteableAbstract     as Essence;

/**
 *  Http Cookie Abstract
 *
 *  @subpackage Http
 */
abstract class CookieAbstract extends Essence implements Factory, Storage
{
    /**
     *  @var string
     */
    protected $name         = 'panda.cookie';

    /**
     *  @var integer
     */
    protected $expire       = 0;

    /**
     *  @var string
     */
    protected $path         = '/';

    /**
     *  @var string
     */
    protected $domain       = null;

    /**
     *  @var bool
     */
    protected $secure       = false;

    /**
     *  @var bool
     */
    protected $http         = true;

    /**
     *  @var string
     */
    protected $input;

    /**
     *  Factory instance.
     *
     *  @return Factory
     */
    public function __construct(
        $name       = null, 
        $input      = null,
        $expire     = 0, 
        $path       = '/', 
        $domain     = null, 
        $secure     = false, 
        $http       = true
    ) {
        $this->name     = $name !== null ? $name : $this->name;
        $this->path     = $path !== null ? $path : '/';
        $this->http     = $http;

        $this->expire   = $expire === 0 ? 0 : time() + $expire;
        $this->domain   = $domain;
        $this->secure   = $secure;

        $this->input    = $input = isset($input) ? $input : (
            array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : null
        );

        if ($decoded = json_decode($input, true) && json_last_error() === 0) {
            $this->replace(is_array($decoded) ? $decoded : ['equal' => $decoded]);
        }
    }

    /**
     *  Factory instance.
     *
     *  @var mixed  $shared
     *
     *  @return \Panda\Http\CookieAbstract
     */
    public static function factory(
        $name       = null, 
        $input      = null,
        $expire     = 0, 
        $path       = '/', 
        $domain     = null, 
        $secure     = false, 
        $http       = true
    ) {
        $name = isset($name) ? $name : 'panda.cookie';

        return new static(
            $name, $input, $expire, $path, $domain, $secure, $http
        );
    }

    /**
     *  Return cookie name param.
     *
     *  @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     *  Return input buffer value.
     *
     *  @return string
     */
    public function input()
    {
        return $this->input;
    }

    /**
     *  Complex expire method. Set 0 for session and grater 0 for expire.
     *
     *  @return mixed
     */
    public function expire($expire = null)
    {
        if ($expire === null) {
            return $this->expire;
        }

        $this->expire = $expire === 0 ? 0 : time() + $expire;

        return $this;
    }

    /**
     *  Complex method path.
     *
     *  @return mixed
     */
    public function path($path = null)
    {
        if ($path === null) {
            return $this->path;
        }

        $this->path = $path;

        return $this;
    }

    /**
     *  Complex method domain.
     *
     *  @return mixed
     */
    public function domain($domain = null)
    {
        if ($domain === null) {
            return $this->domain;
        }

        $this->domain = $domain;

        return $this;
    }

    /**
     *  Complex method secure.
     *
     *  @return mixed
     */
    public function secure($secure = null)
    {
        if ($secure === null) {
            return $this->secure;
        }

        $this->secure = $secure;

        return $this;
    }

    /**
     *  Complex method http.
     *
     *  @return mixed
     */
    public function http($http = null)
    {
        if ($http === null) {
            return $this->http;
        }

        $this->http = $http;

        return $this;
    }

    /**
     *  Return whole shared data.
     *
     *  @return array
     */
    public function all()
    {
        return $this->shared;
    }

    /**
     *  Set similar data with custom expire.
     *
     *  @return array
     */
    public function touch()
    {
        setcookie(
            $this->name, $this->input, $this->expire, $this->path, $this->domain, $this->secure, $this->http
        );

        return $this;
    }

    /**
     *  Short call update method.
     *
     *  @return \Panda\Http\CookieAbstract
     */
    public function update($shared, $equal = null)
    {
        return $this->set($shared, $equal)->save();
    }

    /**
     *  Saving data handler.
     *
     *  @return \Panda\Http\CookieAbstract
     */
    public function save()
    {
        $this->input = json_encode($this->shared);

        setcookie(
            $this->name, $this->input, $this->expire, $this->path, $this->domain, $this->secure, $this->http
        );

        return $this;
    }

    /**
     *  Remove call.
     *
     *  @return \Panda\Http\CookieAbstract
     */
    public function remove()
    {
        setcookie($this->name, null, -1, '/');

        return $this;
    }
}
