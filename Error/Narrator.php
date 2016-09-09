<?php

/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.3
 */
namespace Panda\Error;

/**
 *  Error Params
 *
 *  @subpackage Error
 */
class Narrator extends \Exception
{
    public function __toString()
    {
        return ':: ' . $this->getMessage();
    }
}
