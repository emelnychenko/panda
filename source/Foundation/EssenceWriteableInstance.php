<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Writeable Container Instance
 *
 *  @subpackage Foundation
 */
class EssenceWriteableInstance extends EssenceWriteableAbstract
{
    /**
     *  Single setter - __construct.
     *
     *  @var mixed  $shared
     */
    public function __construct(array $shared = null)
    {
        $this->shared = is_array($shared) ? $shared : func_get_args();
    }
}
