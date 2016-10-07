<?php

use PHPUnit\Framework\TestCase;

use Panda\Essence\Readable;

class ReadableTest extends TestCase
{
    protected $shared = [
        'instance'  => 'null',
        'array'     => [],
        'pull'      => [
            'foo'   => [
                'bar' => 'foo.bar'
            ],
        ],
    ];

    public function testConstructor()
    {
        $readable = new Readable();
        $shared   = $readable->all();

        $this->assertTrue(empty($shared));
        $this->assertInternalType('array', $shared);

        $readable = new Readable($this->shared);
        $shared   = $readable->all();

        $this->assertFalse(empty($shared));
        $this->assertInternalType('array', $shared);
    }

    public function testGet()
    {
        $readable = new Readable($this->shared);

        $this->assertEquals(null, $readable->get('foo'));
        $this->assertInternalType('array', $readable->get('array'));
        $this->assertEquals('foo', $readable->get('bar', 'foo'));

        $this->assertEquals(null, $readable->foo);
        $this->assertInternalType('array', $readable->array);
        $this->assertEquals(null, $readable->bar);
    }

    public function testHas()
    {
        $readable = new Readable($this->shared);

        $this->assertTrue($readable->has('array'));
        $this->assertFalse($readable->has('bar'));

        $this->assertTrue(isset($readable->array));
        $this->assertFalse(isset($readable->bar));
    }

    public function testExists()
    {
        $readable = new Readable($this->shared);

        $this->assertTrue($readable->exists('array'));
        $this->assertFalse($readable->exists('bar'));

        $this->assertTrue($readable->exists('array', 'instance'));
        $this->assertFalse($readable->exists('bar', 'instance'));

        $this->assertTrue($readable->exists(['array', 'instance']));
        $this->assertFalse($readable->exists(['bar', 'instance']));
    }

    public function testOnly()
    {
        $readable   = new Readable($this->shared);
        $only = $readable->only('instance', 'array');

        $this->assertInternalType('array', $only);
        $this->assertEquals(2, count($only));
        $this->assertEquals('null', $only['instance']);

        $only = $readable->only('instance', 'array_in');

        $this->assertInternalType('array', $only);
        $this->assertEquals(1, count($only));

        $only = $readable->only(['instance', 'array_in'], [null, 'one']);

        $this->assertInternalType('array', $only);
        $this->assertEquals(2, count($only));
        $this->assertEquals('one', $only['array_in']);
    }

    public function testExcept()
    {
        $readable   = new Readable($this->shared);
        $only = $readable->except('instance');

        $this->assertInternalType('array', $only);
        $this->assertEquals(2, count($only));
    }

    public function testPull()
    {
        $readable   = new Readable($this->shared);
        $pull       = $readable->pull('pull.foo');

        $this->assertInternalType('array', $pull);
        $this->assertEquals(1, count($pull));

        $pull       = $readable->pull('pull.foo.bar');

        $this->assertInternalType('string', $pull);
        $this->assertEquals('foo.bar', $pull);
    }
}
