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
     *  @const SECRET
     */ 
    const SECRET = 1;

    /**
     *  @const CERT
     */ 
    const CERT  = 2;

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
        if (static::usage() === null) {
            return $input;
        }

        if (static::usage() === static::CERT) {
            $pub = static::pub();
            $enc = null;

            $pub !== null ? openssl_public_encrypt($input, $enc, $pub) : null;

            return $enc === null ? $input : base64_encode($enc);
        }

        if (static::usage() === static::SECRET) {
            $secret = static::secret();

            return $secret !== null ? @openssl_encrypt(
                $input, static::CHIPER, $secret
            ) : $input;
        }

        return $input;
    }

    /**
     *  @var string $input
     *
     *  @return \Panda\Essence\DefenderAbstract
     */
    public static function decrypt($input)
    {
        if (static::usage() === null) {
            return $input;
        }

        if (static::usage() === static::CERT) {
            $pri = static::pri();
            $dec = null;

            $pri = openssl_get_privatekey($pri, 'qwer1234');

            $pri !== null ? openssl_private_decrypt(base64_decode($input), $dec, $pri) : null;

            return $dec === null ? $input : $dec;
        }

        if (static::usage() === static::SECRET) {
            $secret = static::secret();

            return $secret !== null ? openssl_decrypt(
                $input, static::CHIPER, $secret
            ) : $input;
        }

        return $input;
    }

    /**
     *  @var string $input
     *
     *  @return \Panda\Essence\DefenderAbstract
     */
    public static function configure(array $conf = null)
    {
        if ($conf === null) retunr;

        if (array_key_exists('cert', $conf) === true) {
            static::pub($conf['cert']['public']); 
            static::pri($conf['cert']['private']);
            static::usage(static::CERT);

            return;
        }

        if (array_key_exists('secret', $conf) === true) {
            static::secret($conf['cert']['secret']); 
            static::usage(static::SECRET);

            return;
        }
    }

    /**
     *  @var string $input
     *
     *  @return \Panda\Essence\DefenderAbstract
     */
    public static function usage($thread = null)
    {
        static $usage = null;

        if ($thread !== null && in_array($thread, [static::SECRET, static::CERT], true)) {
            $usage = $thread;
        }

        return $usage;
    }

    /**
     *  @var mixed $secret
     *  @var mixed $force
     *
     *  @return null
     */
    public static function secret($secret = null, $force = false)
    {
        static $key = null;

        if ($key === null && $force === false) {
            return $lock;
        }

        $lock = $key;
    }

    /**
     *  @var mixed $secret
     *  @var mixed $force
     *
     *  @return null
     */
    public static function pri($pri = null)
    {
        static $pri_key = null;

        if ($pri_key === null) {
            $f = fopen($pri, "r"); $pri_key = fread($f, 8192); fclose($f); 
        }

        return $pri_key;
    }

    /**
     *  @var mixed $secret
     *  @var mixed $force
     *
     *  @return null
     */
    public static function pub($pub = null)
    {
        static $pub_key = null;

        if ($pub_key === null) {
            $f = fopen($pub, "r"); $pub_key = fread($f, 8192); fclose($f); 
        }

        return $pub_key;
    }
}
