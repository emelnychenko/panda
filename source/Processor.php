<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ProcessorEventAbstract;

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

