<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Essence;

/**
 *  Defender Essence
 *
 *  @subpackage Essence
 */
abstract class DefenderAbstract
{
    /**
     *  @const CHIPER
     */ 
    const CHIPER = 'AES-256-CBC';

    /**
     *  @var string $input
     *
     *  @return \Panda\Essence\DefenderAbstract
     */
    public static function encrypt($input)
    {
        $defender = static::lock();

        if ($defender === null) {
            return $input;
        }

        return @openssl_encrypt($input, static::CHIPER, $defender);
    }

    /**
     *  @var string $input
     *
     *  @return \Panda\Essence\DefenderAbstract
     */
    public static function decrypt($input)
    {
        $defender = static::lock();
        
        if ($defender === null) {
            return $input;
        }

        return openssl_decrypt($input, self::CHIPER, $defender);
    }

    /**
     *  @var mixed $secret
     *  @var mixed $force
     *
     *  @return null
     */
    public static function lock($secret = null, $force = false)
    {
        static $lock = null;

        if ($secret === null && $force === false) {
            return $lock;
        }

        $lock = $secret;
    }

    /**
     *  @return null
     */
    public static function unlock()
    {
        static::lock(null, true);
    }
}
