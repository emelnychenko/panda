<?php

/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.3
 */
namespace Panda\Secure;

/**
 *  Secure Guard Interface
 *
 *  @subpackage Secure
 */
interface GuardInterface
{
    /**
     *  @var string $input
     */
    public function encrypt($input);

    /**
     *  @var string $input
     */
    public function decrypt($input);
}
