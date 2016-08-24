<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Writeable Container Interface
 *
 *  @subpackage Support
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
    public function add(array $container = null);

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
