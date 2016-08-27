<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\ProcessorEventAbstract;

/**
 *  Panda Processor
 *
 *  @subpackage Framework
 */
abstract class Processor extends ProcessorEventAbstract implements ProcessorInterface
{
    public function session($container = 'panda.app')
    {
        return new Session($container);
    }
}

