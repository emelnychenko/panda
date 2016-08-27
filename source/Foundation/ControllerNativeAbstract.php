<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Controller Native Abstract
 *
 *  @subpackage Foundation
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

