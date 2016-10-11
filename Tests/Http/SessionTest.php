<?php

use PHPUnit\Framework\TestCase;

use Panda\Http\Session;

class SessionTest extends TestCase
{
    protected $name     = 'app';

    /**
     *  @runInSeparateProcess
     */
    public function testConstructor()
    {
        new Session($this->name);
        $session    = Session::factory($this->name);

        $this->assertInstanceOf(Session::class, $session);
    }

    /**
     *  @runInSeparateProcess
     */
    public function testName()
    {
        $this->assertEquals($this->name, Session::factory($this->name)->name());
    }
}
