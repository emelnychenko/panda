<?php

use PHPUnit\Framework\TestCase;

use Panda\Http\Response;
use Panda\Essence\Writeable;

class ResponseTest extends TestCase
{
    protected $content  = 'Hello test.';

    public function testConstructor()
    {
        $response = new Response($this->content);

        $this->assertEquals($response->__toString(), $this->content);

        $response = Response::factory($this->content);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($response->content(), $this->content);
        $this->assertEquals($response->__toString(), $this->content);
    }

    public function testContent()
    {
        $response = Response::factory($this->content);

        $this->assertEquals($response->content(), $this->content);
        $this->assertEquals($response->__toString(), $this->content);

        $this->assertInstanceOf(Response::class, $response->content('foo'));

        $this->assertEquals($response->content(), 'foo');
        $this->assertEquals($response->__toString(), 'foo');
    }

    public function testStatus()
    {
        $response = Response::factory($this->content);

        $this->assertEquals($response->status(), 200);

        $this->assertInstanceOf(Response::class, $response->status(404));

        $this->assertEquals($response->status(), 404);
    }

    public function testHeader()
    {
        $response = Response::factory($this->content);

        $this->assertInstanceOf(Writeable::class, $response->header());
        $this->assertInstanceOf(Response::class, $response->header('foo', 'bar'));
        $this->assertInstanceOf(Response::class, $response->header(['bar' => 'foo']));
        $this->assertEquals('foo', $response->header('bar'));
        $this->assertEquals('bar', $response->header('foo'));
    }
}
