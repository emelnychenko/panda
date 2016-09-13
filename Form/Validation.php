<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Form;

use Panda\Form\Input;

/**
 *  Panda Form
 *
 *  @subpackage Framework
 */
class Validation
{
    public static function integer($equal)
    {
        return (bool) filter_var($equal, FILTER_VALIDATE_INT);
    }

    public static function int($equal)
    {
        return static::integer($equal);
    }

    public static function numeric($equal)
    {
        return static::integer($equal);
    }

    public static function num($equal)
    {
        return static::integer($equal);
    }

    public static function float($equal)
    {
        return (bool) filter_var($equal, FILTER_VALIDATE_FLOAT);
    }

    public static function ip($equal)
    {
        return (bool) filter_var($equal, FILTER_VALIDATE_IP);
    }

    public static function alpha($equal)
    {
        return (bool) preg_replace('/^[a-zA-Z]+$/i', '', $equal);
    }

    public static function alphanumeric($equal)
    {
        return (bool) preg_replace('/^[a-zA-Z0-9]+$/i', '', $equal);
    }

    public static function alnum($equal)
    {
        return static::alphanumeric($equal);
    }

    public static function email($equal)
    {
        return (bool) filter_var($equal, FILTER_VALIDATE_EMAIL);
    }

    public static function url()
    {
        return (bool) filter_var($equal, FILTER_VALIDATE_URL);
    }

    public static function regexp($equal, $pattern)
    {
        return (bool) preg_match($pattern, $equal);   
    }

    public static function min($equal, $size)
    {
        return strlen($equal) >= $size;
    }

    public static function max($equal, $size)
    {
        return strlen($equal) <= $size;
    }

    public static function between($equal, $min, $max)
    {
        $length = strlen($equal);

        return $length >= $min && $length <= $max;
    }

    public static function required($equal)
    {
        return !empty($equal);
    }
}
