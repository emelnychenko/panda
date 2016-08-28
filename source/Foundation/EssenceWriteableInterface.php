<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Writeable Container Interface
 *
 *  @subpackage Foundation
 */
interface EssenceWriteableInterface
{
    /**
     *  Set container array data.
     *
     *  @var array $container
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function add(array $shared = null);

    /**
     *  Set container by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function set($key, $equal);

    /**
     *  Set magic container by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function __set($key, $equal);
}
