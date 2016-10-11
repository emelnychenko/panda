<?php

use PHPUnit\Framework\TestCase;

use Panda\Essence\Writeable;

class WriteableTest extends TestCase
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
        $writeable = new Writeable();
        $shared   = $writeable->all();

        $this->assertTrue(empty($shared));
        $this->assertInternalType('array', $shared);

        $writeable = new Writeable($this->shared);
        $shared   = $writeable->all();

        $this->assertFalse(empty($shared));
        $this->assertInternalType('array', $shared);
    }

    public function testSet()
    {
        $writeable = new Writeable($this->shared);

        $this->assertInstanceOf(Writeable::class, $writeable->set('foo', 'bar'));
        $this->assertEquals('bar', $writeable->get('foo'));
        $this->assertInstanceOf(Writeable::class, $writeable->set(['foo' => 'bar']));
        $this->assertEquals('bar', $writeable->get('foo'));
    }

    public function testReplace()
    {
        $writeable = new Writeable($this->shared);

        $this->assertInstanceOf(Writeable::class, $writeable->replace(['foo' => 'bar']));
        $this->assertEquals('bar', $writeable->get('foo'));
    }

    public function testPush()
    {
        $writeable = new Writeable($this->shared);

        $this->assertInstanceOf(Writeable::class, $writeable->push('foo.bar.instance', 'bar'));
        $this->assertEquals('bar', $writeable->pull('foo.bar.instance'));
    }
}
