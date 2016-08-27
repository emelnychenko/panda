<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

use Panda\Foundation\Http\ProcessorEventAbstract;

use Panda\Session;

/**
 *  Http Processor Abstract
 *
 *  @subpackage Http
 */
abstract class Processor extends ProcessorEventAbstract implements ProcessorInterface
{
    public function session($container = 'panda.app')
    {
        return new Session($container);
    }
}

