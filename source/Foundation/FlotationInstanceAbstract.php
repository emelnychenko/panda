<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Flotation Instance Abstract
 *
 *  @subpackage Foundation
 */
abstract class FlotationInstanceAbstract implements FlotationInstanceInterface
{
    /**
     *  @var bool
     */ 
    private $skipped = false;

    /**
     *  Set false for skipping.
     *
     *  @return \Panda\Foundation\Support\FlotationInstanceAbstract
     */ 
    public function prevent()
    {
        $this->skipped = false;

        return $this;
    }

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Foundation\Support\FlotationInstanceAbstract
     */ 
    public function next()
    {
        $this->skipped = true;

        return $this;
    }

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Foundation\Support\FlotationInstanceAbstract
     */ 
    public function skip()
    {
        $this->skipped = true;

        return $this;
    }

    /**
     *  Return floating desiccion
     *
     *  @return bool
     */ 
    public function skipped()
    {
        return $this->skipped;
    }
}
