<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
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
     *  Get cookie essence, cookie layer model.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function cookie($key = null, $default = null);

    /**
     *  Get session layer model
     *
     *  @var mixed $key
     *
     *  @return \Panda\Http\Session
     */
    public function session($key = null);

    /**
     *  Get files essence, files param.
     *
     *  @var mixed $key
     *  @var mixed $default
     *
     *  @return mixed
     */
    public function files($key = null, $default = null);

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
     *  Verify if request is json content type.
     *
     *  @return bool
     */
    public function json();

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return bool
     */
    public function xhr();

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
     *  Compare url path.
     *
     *  @return bool
     */
    public function is($url = '/');

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
    public function protocol();

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function host();

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function port();

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
    public function rundir();

    /**
     *  Get request method or verify if method is $question.
     *
     *  @return mixed
     */
    public function method();

    /**
     *  Get client locale or verify if locale is $question.
     *
     *  @var mixed $question
     *
     *  @return mixed
     */
    public function locale($locale = 'en');
}
