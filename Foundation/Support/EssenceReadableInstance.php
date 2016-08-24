<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Readable Container Instance
 *
 *  @subpackage Support
 */
class EssenceReadableInstance extends EssenceReadableAbstract
{
    /**
     *  Single setter - __construct.
     *
     *  @var mixed  $container
     */
    public function __construct($container)
    {
        $this->container = is_array($container) ? $container : func_get_args();
    }
}
