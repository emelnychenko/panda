<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Processor Event Abstract
 *
 *  @subpackage Foundation
 */
abstract class ProcessorEventAbstract extends FlotationInstanceAbstract implements ProcessorEventInterface 
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
        $this->request  = $request;
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

    /**
     *  Execute processor.
     *
     *  @return mixed
     */
    abstract function handle();
}

