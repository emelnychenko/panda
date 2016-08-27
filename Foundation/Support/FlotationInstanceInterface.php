<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Flotation Instance Interface
 *
 *  @subpackage Support
 */
interface FlotationInstanceInterface
{
    /**
     *  Set false for skipping.
     *
     *  @return \Panda\Foundation\Support\FlotationInstanceAbstract
     */ 
    public function prevent();

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Foundation\Support\FlotationInstanceAbstract
     */ 
    public function next();

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Foundation\Support\FlotationInstanceAbstract
     */ 
    public function skip();

    /**
     *  Return floating desiccion
     *
     *  @return bool
     */ 
    public function skipped();
}
