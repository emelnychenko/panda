<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.6.0
 */

namespace Panda\NoSQL;

use Panda\NoSQL\Redis\Hash;

/**
 *  NoSQL Redis Abstract
 *
 *  @subpackage NoSQL
 */
class Redis extends Hash
{
    /**
     *  @var string
     */
    protected $prefix       = 'graph';

    /**
     *  @var string
     */
    protected $key          = 'friendship';
}
