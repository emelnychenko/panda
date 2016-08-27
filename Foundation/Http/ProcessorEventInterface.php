<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http;

/**
 *  Processor Event Interface
 *
 *  @subpackage Http
 */
interface ProcessorEventInterface 
{
    /**
     *  Get request, request input.
     *
     *  @var mixed $input
     *
     *  @return mixed
     */
    public function request($input = null);
}

