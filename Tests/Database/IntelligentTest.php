<?php

namespace Panda\Tests\Database;

use Panda\Database\Intelligent               as T;
use Panda\Tests\Database\IntelligentUnit    as U;

class IntelligentTest extends \PHPUnit\Framework\TestCase
{
    public function testSharedOriginOriginate()
    {
        $t = U::factory();

        $this->assertTrue(is_array($t->shared()));
        $this->assertTrue(is_array($t->origin()));
        $this->assertEquals($t->table(), 'user');

        $this->assertInstanceOf(T::class, $t->set('name', 'foo'));
        $this->assertFalse(json_encode($t->origin()) === json_encode($t->shared()));
        $this->assertInstanceOf(T::class, $t->originate());
        $this->assertTrue(json_encode($t->origin()) === json_encode($t->shared()));
    }

    public function testTimestampDatetimeDateTime()
    {
        $t = U::factory();

        $this->assertEquals($t->datetime(), 'Y.m.d H:i');
        $this->assertEquals($t->date(), 'Y.m.d');
        $this->assertEquals($t->time(), 'H:i');
        $this->assertEquals($t->timestamp(), 'date');
        $this->assertEquals($t->timestamp(true), date('Y.m.d'));

        $this->assertInstanceOf(T::class, $t->timestamp(true, 'created_at'));
        $this->assertInstanceOf(T::class, $t->timestamp(true, 'updated_at'));

        $this->assertEquals(date('Y.m.d'), $t->created_at);
        $this->assertEquals(date('Y.m.d'), $t->updated_at);
    }

    public function testIncrement()
    {
        $t = U::factory();

        $this->assertEquals($t->increment(), false);
    }
}
