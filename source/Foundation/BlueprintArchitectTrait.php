<?php
/**
 *  @category   Blink
 *  @author     Eugen Melnychenko
 *  @version    v1.0
 */

namespace Panda\Foundation;

use Closure;

/**
 *  Blueprint Architect Expansion
 *
 *  @package Database
 */
trait BlueprintArchitectTrait
{
    protected function __divide_assign($mixed, $equal = null, Closure $callback)
    {
        if (
            is_array($mixed)
        ) {
            foreach ($mixed as $_mixed => $_equal) {
                if (
                    is_numeric($_mixed)
                ) {
                    call_user_func($callback, $_equal, null);
                } else {
                    call_user_func($callback, $_mixed, $_equal);
                }
            }
        } elseif (
            is_string($mixed) && is_null($equal)
        ) {
            call_user_func($callback, $mixed, null);
        } elseif (
            is_string($mixed) && is_string($equal)
        ) {
            call_user_func($callback, $mixed, $equal);
        }
        return $this;
    }

    protected function __include_assign(&$equal, array $pattern, $include = null)
    {
        if (
            !in_array($equal, $pattern, true)
        ) {
            $equal = $include;
        }
        return $this;
    }

    protected function __implode_assoarr(array $dataset, $pattern)
    {
        $container = array();
        foreach ($dataset as $_key => $_value) {
            array_push(
                $container, sprintf($pattern, $_key, $_value)
            );
        }
        return $container;
    }
}