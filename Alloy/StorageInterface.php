<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Alloy;

/**
 *  Storage Alloy
 *
 *  @subpackage Alloy
 */
interface StorageInterface
{
    /**
     *  Factory instance.
     *
     *  @return Factory
     */
    public function update($shared, $equal = null);

    /**
     *  Factory instance.
     *
     *  @return Factory
     */
    public function save();

    /**
     *  Factory instance.
     *
     *  @return Factory
     */
    public function remove();
}
