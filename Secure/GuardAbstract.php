<?php

/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.3
 */
namespace Panda\Secure;

use Panda\Essence\Readable;
use Panda\Error\Narrator;

/**
 *  Secure Guard Abstract
 *
 *  @subpackage Secure
 */
class GuardAbstract
{
    /**
     *  @const KEY
     */ 
    const KEY       = 'key';

    /**
     *  @const RSA
     */ 
    const RSA       = 'rsa';

    /**
     *  @const ECDSA
     */ 
    const ECDSA     = 'ecdsa';

    public static function register(array $param = null)
    {
        if ($param === null) return;

        $essence = Readable::factory($param);

        if (
            $essence->adapter !== null && !in_array($essence->adapter, ['key', 'rsa', 'ecdsa'], true)
        ) {
            throw new Narrator('Config defender[\'adapter\'] missed or invalid.');
        }

        static::strict($essence->get('strict', false));

        if ($essence->adapter === static::KEY) {
            if ($essence->secret === null) {
                throw new Narrator('For \'key\' implements need valid \'secret\'.');
            }

            return static::interface(Key::factory($essence->secret));
        }

        if ($essence->adapter === static::RSA) {
            if ($essence->private === null && $essence->public === null) {
                throw new Narrator('For \'rsa\' implements need valid \'public\' or \'private\'.');
            }

            return static::interface(RSA::factory($essence->private, $essence->public));
        }

        if ($essence->adapter === static::ECDSA) {
            if ($essence->private === null && $essence->public === null) {
                throw new Narrator('For \'ecdsa\' implements need valid \'public\' or \'private\'.');
            }

            return static::interface(RSA::factory($essence->private, $essence->public));
        }
    }

    public static function encrypt($input)
    {
        $interface = static::interface();

        if ($interface === null && static::strict() === true) {
            throw new Narrator('Defender strict sees registred interface.');
        }

        return $interface === null ? $input : $interface->encrypt($input);
    }

    public static function decrypt($input)
    {
        $interface = static::interface();

        if ($interface === null && static::strict() === true) {
            throw new Narrator('Defender strict sees registred interface.');
        }

        return $interface === null ? $input : $interface->decrypt($input);
    }

    public static function interface(GuardInterface $interface = null)
    {
        static $shared = null;

        if ($shared === null && $interface !== null) {
            $shared = $interface;
        }

        return $shared;
    }

    public static function strict($answer = null)
    {
        static $strict = null;

        if ($strict === null && $answer !== null) {
            $strict = $answer;
        }

        return $strict;
    }
}
