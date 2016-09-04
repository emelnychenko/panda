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
 *  Readable Essence
 *
 *  @subpackage Essence
 */
abstract class FloatedAbstract
{
    /**
     *  @var bool
     */ 
    protected $pass = false;

    /**
     *  Set false for skipping.
     *
     *  @return \Panda\Essence\FloatedAbstract
     */ 
    public function prevent()
    {
        $this->skipped = false;

        return $this;
    }

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Essence\FloatedAbstract
     */ 
    public function pass()
    {
        $this->pass = true;

        return $this;
    }

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Essence\FloatedAbstract
     */ 
    public function next()
    {
        return $this->pass();
    }

    /**
     *  Return floating desiccion
     *
     *  @return bool
     */ 
    public function passed()
    {
        return $this->pass;
    }
}
