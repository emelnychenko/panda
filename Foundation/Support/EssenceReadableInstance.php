<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Readable Container Factory
 *
 *  @subpackage Support
 */
class EssenceReadableInstance extends EssenceReadableAbstract
{
    public function __construct($container)
    {
        $this->container = is_array($container) ? $container : func_get_args();
    }
}

