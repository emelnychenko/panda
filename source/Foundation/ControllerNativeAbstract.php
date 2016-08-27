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
    protected $support;

    /**
     *  Create instance
     *
     *  @var \Panda\Foundation\Http\ClientRequestedInterface $request
     */
    public function __construct(SupportServicesInterface $support)
    {
        $this->support = $support; 
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
            return $this->support('request');
        }

        return $this->support('request')->input($input);
    }

    public function support($service = null)
    {
        if (
            $service === null
        ) {
            return $this->support;
        }

        return $this->support->get($service);
    }
}

