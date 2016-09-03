<?php

use PHPUnit\Framework\TestCase;

use Panda\Http\Request;
use Panda\Http\Cookie;
use Panda\Http\Session;
use Panda\Essence\Readable;

class RequestTest extends TestCase
{
    protected $content  = 'Hello test.';

    public function testConstructor()
    {
        $injection  = ['foo' => 'bar'];
        $request    = new Request($injection, $injection, $injection, $injection, $injection);

        $this->assertInstanceOf(Readable::class, $request);
        $this->assertArrayHasKey('foo', $request->arrayable());
    }

    /**
     *  @runInSeparateProcess
     */
    public function testBranches()
    {
        $injection  = ['foo' => 'bar'];
        $request    = new Request($injection, $injection, $injection, $injection, $injection);

        foreach (['query', 'request', 'files', 'server'] as $branch) {
            $this->assertInstanceOf(Readable::class, $request->{$branch});
            $this->assertInstanceOf(Readable::class, $request->{$branch}());
            $this->assertTrue(is_string($request->{$branch}('foo')));
        }

        $this->assertInstanceOf(Readable::class, $request->cookie);
        $this->assertInstanceOf(Readable::class, $request->cookie());
        $this->assertInstanceOf(Cookie::class, $request->cookie('foo'));
        $this->assertInstanceOf(Session::class, $request->session('foo'));
    }

    public function testRequestComparison()
    {
        $request    = Request::factory();

        $this->assertTrue(is_bool($request->json()));
        $this->assertTrue(is_bool($request->xhr()));
        $this->assertTrue(is_bool($request->is('/')));
    }

    public function testShortCallServer()
    {
        $request    = Request::factory();

        $this->assertTrue(is_string($request->uri()));
        $this->assertTrue(is_string($request->url()));
        $this->assertTrue(is_string($request->ip()));
        $this->assertTrue(is_string($request->agent()));
        $this->assertTrue(is_string($request->protocol()));
        $this->assertTrue(is_string($request->host()));
        $this->assertTrue(is_string($request->port()));
        $this->assertTrue(is_string($request->domain()));
        $this->assertTrue(is_string($request->rundir()));
        $this->assertTrue(is_string($request->method()));
        $this->assertTrue(is_string($request->locale()));
    }
}
