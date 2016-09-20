<?php

namespace Panda\Tests\Database;

use Panda\Database\Intelligent as T;

class IntelligentUnit extends T
{
    protected $table = 'user';

    protected $increment    = false;

    /** 
     *  @var string 
     */ 
    protected $timestamp    = 'date';

    /** 
     *  @var string 
     */ 
    protected $datetime     = 'Y.m.d H:i';

    /** 
     *  @var string 
     */ 
    protected $date         = 'Y.m.d';

    /** 
     *  @var string 
     */ 
    protected $time         = 'H:i';

    public function origin()
    {
        return $this->origin;
    }

    public function shared()
    {
        return $this->shared;
    }

    public function increment()
    {
        return $this->increment;
    }

    public function datetime()
    {
        return $this->datetime;
    }

    public function date()
    {
        return $this->date;
    }

    public function time()
    {
        return $this->time;
    }
}
