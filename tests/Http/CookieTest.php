<?php

use PHPUnit\Framework\TestCase;

use Panda\Http\Cookie;

class CookieTest extends TestCase
{
    protected $name     = 'app';

    protected $input    = '{"foo":"bar"}';

    protected $expire   = '3600';

    protected $path     = '/';

    protected $domain   = 'panda.app';

    protected $secure   = false;

    protected $http     = false;

    public function testConstructor()
    {
        $cookie     = new Cookie($this->name, $this->input);
        $cookie     = Cookie::factory($this->name, $this->input);
    }

    public function testName()
    {
        $this->assertEquals($this->name, Cookie::factory($this->name)->name());
    }

    public function testInput()
    {
        $this->assertEquals($this->input, Cookie::factory($this->name, $this->input)->input());
    }

    public function testExpire()
    {
        $cookie = Cookie::factory($this->name, $this->input, $this->expire);

        $this->assertEquals(time() + $this->expire, $cookie->expire());

        $this->assertInstanceOf(Cookie::class, $cookie->expire(0));

        $this->assertEquals(0, $cookie->expire());
    }


    public function testPath()
    {
        $cookie = Cookie::factory($this->name, $this->input, $this->expire, $this->path);

        $this->assertEquals($this->path, $cookie->path());

        $this->assertInstanceOf(Cookie::class, $cookie->path('/self'));

        $this->assertEquals('/self', $cookie->path());
    }


    public function testDomain()
    {
        $cookie = Cookie::factory($this->name, $this->input, $this->expire, $this->path, $this->domain);

        $this->assertEquals($this->domain, $cookie->domain());

        $this->assertInstanceOf(Cookie::class, $cookie->domain('panda.work'));

        $this->assertEquals('panda.work', $cookie->domain());
    }

    public function testSecure()
    {
        $cookie = Cookie::factory($this->name, $this->input, $this->expire, $this->path, $this->domain, $this->secure, $this->http);

        $this->assertEquals($this->secure, $cookie->secure());

        $this->assertInstanceOf(Cookie::class, $cookie->secure(true));

        $this->assertEquals(true, $cookie->secure());
    }

    public function testHttpOnly()
    {
        $cookie = Cookie::factory($this->name, $this->input, $this->expire, $this->path, $this->domain, $this->secure, $this->http);

        $this->assertEquals($this->http, $cookie->http());

        $this->assertInstanceOf(Cookie::class, $cookie->http(true));

        $this->assertEquals(true, $cookie->http());
    }
}
