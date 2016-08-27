<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Controller Native Interface
 *
 *  @subpackage Foundation
 */
interface ControllerNativeInterface 
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

