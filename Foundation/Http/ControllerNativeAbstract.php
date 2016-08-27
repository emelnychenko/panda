<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http;

use Panda\Foundation\Support\FlotationInstanceAbstract;

/**
 *  Controller Native Abstract
 *
 *  @subpackage Http
 */
abstract class ControllerNativeAbstract extends FlotationInstanceAbstract implements ControllerNativeInterface
{
    /**
     *  @var \Panda\Foundation\Http\ClientRequestedInterface
     */ 
    protected $request;

    /**
     *  Create instance
     *
     *  @var \Panda\Foundation\Http\ClientRequestedInterface $request
     */
    public function __construct(ClientRequestedInterface $request)
    {
        $this->request = $request; 
    }

    /**
     *  Get request, request input.
     *
     *  @var mixed $input
     *
     *  @return mixed
     */
    public function request($input = null)
    {
        if (
            $input === null
        ) {
            return $this->request;
        }

        return $this->request->input($input);
    }
}

