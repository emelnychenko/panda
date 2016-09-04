<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http\Controller;

use Panda\Http\ControllerAbstract       as Controller;

/**
 *  Http Controller Guard Abstract
 *
 *  @subpackage Http
 */
abstract class GuardAbstract extends Controller
{
    abstract public function inspect();
}
