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
class Sanitation
{
    public static function integer($equal)
    {
        return filter_var($equal, FILTER_SANITIZE_NUMBER_INT);
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
        return filter_var($equal, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    public static function alpha($equal)
    {
        return preg_replace('/[^a-zA-Z]+/i', '', $equal);
    }

    public static function alphanumeric($equal)
    {
        return preg_replace('/[^a-zA-Z0-9]+/i', '', $equal);
    }

    public static function alnum($equal)
    {
        return static::alphanumeric($equal);
    }

    public static function email($equal)
    {
        return filter_var($equal, FILTER_SANITIZE_EMAIL);
    }
}
