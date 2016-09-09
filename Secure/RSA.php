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
 *  Secure RSA
 *
 *  @subpackage Secure
 */
class RSA implements GuardInterface, FactoryInterface
{
    /**
     *  @var string
     */
    protected $public;

    /**
     *  @var string
     */
    protected $private;

    /**
     *  @var string $private
     *  @var string $public
     */
    public function __construct($private = null, $public = null)
    {
        if ($private !== null) {
            $f = fopen($private, "r"); $this->private = fread($f, 8192); fclose($f);
        }

        if ($public !== null) {
            $f = fopen($public, "r"); $this->public = fread($f, 8192); fclose($f);
        }
    }

    /**
     *  @var string $private
     *  @var string $public
     */
    public static function factory($private = null, $public = null)
    {
        return new static($private, $public);
    }

    /**
     *  Use public encryption.
     *
     *  @var string $input
     */
    public function encrypt($input)
    {
        if ($this->public === null) {
            throw new Narrator('Public certificate missed.');
        }

        openssl_public_encrypt($input, $encrypted, $this->public);

        return base64_encode($encrypted);
    }

    /**
     *  Use private decryption.
     *
     *  @var string $input
     */
    public function decrypt($input)
    {
        if ($this->private === null) {
            throw new Narrator('Private certificate missed.');
        }

        openssl_private_decrypt(base64_decode($input), $decrypted, $this->private);

        return $decrypted;
    }
}
