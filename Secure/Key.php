<?php

/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.3
 */
namespace Panda\Secure;

use Panda\Alloy\FactoryInterface;

use Panda\Error\Narrator;

/**
 *  Secure Key
 *
 *  @subpackage Secure
 */
class Key implements GuardInterface, FactoryInterface
{
    /**
     *  @const CHIPER
     */ 
    const CHIPER    = 'AES-256-CBC';

    /**
     *  @var string
     */
    protected $secret;

    /**
     *  @var string $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     *  @var string $secret
     */
    public static function factory($secret = null)
    {
        return new static($secret);
    }

    /**
     *  Use secret encryption.
     *
     *  @var string $input
     */
    public function encrypt($input)
    {
        if ($this->secret === null) {
            throw new Narrator('Secret key missed.');
        }

        return @openssl_encrypt($input, static::CHIPER, $this->secret);
    }

    /**
     *  Use secret encryption.
     *
     *  @var string $input
     */
    public function decrypt($input)
    {
        if ($this->secret === null) {
            throw new Narrator('Secret key missed.');
        }

        return openssl_decrypt($input, static::CHIPER, $this->secret);
    }
}
