<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http\ResponseImplementation;

use Panda\Foundation\Support\EssenceWriteableInstance;

/**
 *  Client Response Essence Headers
 *
 *  @subpackage Http
 */
class EssenceHeaders extends EssenceWriteableInstance
{
    /**
     *  Simple __construct.
     *
     *  @var mixed  $container
     */
    public function __construct($container)
    {
        $this->container = is_array($container) ? $container : func_get_args();
    }
}

