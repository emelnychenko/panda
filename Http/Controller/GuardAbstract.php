<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http\Controller;

use Panda\Deploy\Applique               as Applique;
use Panda\Http\ControllerAbstract       as Controller;

/**
 *  Http Controller Guard Abstract
 *
 *  @subpackage Http
 */
abstract class GuardAbstract extends Controller
{
    public function __construct(Applique $applique = null)
    {
        $applique->register(get_called_class(), $this);

        parent::__construct($applique);
    }

    abstract public function inspect();
}
