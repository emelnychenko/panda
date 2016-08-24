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
     *  @return bool
     */
    public function ajax();
}

