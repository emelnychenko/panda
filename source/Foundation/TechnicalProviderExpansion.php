<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */
namespace Panda\Foundation;

trait TechnicalProviderExpansion
{
    protected function tpe_pair_iterator($keys, $equal = null, $callback)
    {
        if (
            is_array($keys) && $equal === null
        ) {
            foreach ($keys as $key => $equal) {
                call_user_func($callback, $key, $equal);
            }
        } else {
            call_user_func($callback, $keys, $equal);
        }

        return;
    }
}
