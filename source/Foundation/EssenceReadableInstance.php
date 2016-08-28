<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Readable Container Instance
 *
 *  @subpackage Foundation
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
        $this->shared = is_array($container) ? $container : func_get_args();
    }

    /**
     *  Get whole shared.
     *
     *  @return array
     */
    public function all()
    {
        return $this->shared;
    }
}
