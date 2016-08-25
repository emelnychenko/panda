<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http;

/**
 *  Client Request Interface
 *
 *  @subpackage Http
 */
interface ClientRequestedInterface
{
    /**
     *  Get main essence.
     *
     *  @return @var \Panda\Foundation\Support\EssenceReadableInstance
     */
    public function source();
    
    /**
     *  Get only array keys from main essence.
     *
     *  @var mixed $key
     *
     *  @return array
     */
    public function only($keys);

    /**
     *  Get actual array dataset from main essence.
     *
     *  @return array
     */
    public function all();

    /**
     *  Get request essence, request param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function input($key = null, $default = null);

    /**
     *  Get query essence, query param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function query($key = null, $default = null);

    /**
     *  Get request essence, request param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function request($key = null, $default = null);

    /**
     *  Get cookie essence, cookie param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function cookie($key = null, $default = null);

    /**
     *  Get files essence, files param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function file($key = null, $default = null);

    /**
     *  Get server essence, server param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function server($key = null, $default = null);

    /**
     *  Get json output value or container.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function json($key = null, $default = null);

    /**
     *  Verify if request is json content type.
     *
     *  @return bool
     */
    public function is_json();

    /**
     *  Get uri path.
     *
     *  @return string
     */
    public function uri();

    /**
     *  Get url path (without uri).
     *
     *  @return string
     */
    public function url();

    /**
     *  Get client IP.
     *
     *  @return string
     */
    public function ip();

    /**
     *  Get client user agent.
     *
     *  @return string
     */
    public function agent();

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function domain();

    /**
     *  Get document root.
     *
     *  @return string
     */
    public function docroot();

    /**
     *  Get request method or verify if method is $question.
     *
     *  @return mixed
     */
    public function method($question = null);

    /**
     *  Get client locale or verify if locale is $question.
     *
     *  @return mixed
     */
    public function locale($question = null);

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return mixed
     */
    public function xhr();

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return bool
     */
    public function ajax();
}

