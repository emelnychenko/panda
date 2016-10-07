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
 *  Swimmer Essence Abstract
 *
 *  @subpackage Essence
 */
abstract class SwimmerAbstract
{
    /**
     *  @var bool
     */ 
    protected $passed = false;

    /**
     *  Set false for skipping.
     *
     *  @return \Panda\Essence\SwimmerAbstract
     */ 
    public function prevent()
    {
        $this->passed = false;

        return $this;
    }

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Essence\SwimmerAbstract
     */ 
    public function pass()
    {
        $this->passed = true;

        return $this;
    }

    /**
     *  Set true for skipping.
     *
     *  @return \Panda\Essence\SwimmerAbstract
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
        return $this->passed;
    }
}
