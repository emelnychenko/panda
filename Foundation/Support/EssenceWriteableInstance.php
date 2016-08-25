<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Writeable Container Instance
 *
 *  @subpackage Support
 */
class EssenceWriteableInstance extends EssenceWriteableAbstract
{
    /**
     *  Single setter - __construct.
     *
     *  @var mixed  $container
     */
    public function __construct(array $container = null)
    {
        $this->container = is_array($container) ? $container : func_get_args();
    }
}
