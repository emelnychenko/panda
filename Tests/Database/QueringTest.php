<?php

namespace Panda\Tests\Database;

use Panda\Tests\Database\QueringUnit as Q;

class QueringTest extends \PHPUnit\Framework\TestCase
{
    public function testStatements()
    {
        $q = Q::factory();

        $this->assertEquals(null, $q->statement());

        $this->assertInstanceOf(Q::class, $q->select());
        $this->assertEquals(Q::SELECT, $q->statement());

        $this->assertInstanceOf(Q::class, $q->insert('user'));
        $this->assertEquals(Q::INSERT, $q->statement());

        $this->assertInstanceOf(Q::class, $q->update('user'));
        $this->assertEquals(Q::UPDATE, $q->statement());

        $this->assertInstanceOf(Q::class, $q->delete());
        $this->assertEquals(Q::DELETE, $q->statement());
    }

    public function testFromTable()
    {
        $q = Q::factory();

        $this->assertInstanceOf(Q::class, $q->from('user_0'));
        $this->assertEquals('user_0', $q->table());

        $this->assertInstanceOf(Q::class, $q->insert('user_1'));
        $this->assertEquals('user_1', $q->table());

        $this->assertInstanceOf(Q::class, $q->update('user_2'));
        $this->assertEquals('user_2', $q->table());

        $this->assertInstanceOf(Q::class, $q->from('user_3', '3'));
        $this->assertEquals('user_3 AS 3', $q->table());

        $this->assertInstanceOf(Q::class, $q->insert('user_4', '4'));
        $this->assertEquals('user_4 AS 4', $q->table());

        $this->assertInstanceOf(Q::class, $q->update('user_5', '5'));
        $this->assertEquals('user_5 AS 5', $q->table());
    }

    public function testSet()
    {
        $q = Q::factory();

        $this->assertInstanceOf(Q::class, $q->set('table_0', '0'));
        $this->assertInstanceOf(Q::class, $q->set(['table_1' => '1', 'table_2' => '2']));

        $s = $q->set();
        $b = $q->bind();

        foreach(['table_0' => '0', 'table_1' => '1', 'table_2' => '2'] as $c => $v) {
            $this->assertArrayHasKey($c, $s);
            $this->assertArrayHasKey($s[$c], $b);

            $this->assertEquals($v, $b[$s[$c]]);
        }
    }

    public function testWhereHaving()
    {
        $q = Q::factory();

        $this->assertInstanceOf(Q::class, $q->where('table_0', '0'));
        $this->assertInstanceOf(Q::class, $q->where('table_0 = 0'));
        $this->assertInstanceOf(Q::class, $q->where(['table_1' => '1', 'table_2' => '2', 'table_1 = 1', 'table_2 = 2']));

        $this->assertInstanceOf(Q::class, $q->having('table_0', '0'));
        $this->assertInstanceOf(Q::class, $q->where('table_0 = 0'));
        $this->assertInstanceOf(Q::class, $q->having(['table_1' => '1', 'table_2' => '2', 'table_1 = 1', 'table_2 = 2']));
    }

    public function testOrderGroup()
    {
        $q = Q::factory();

        $this->assertInstanceOf(Q::class, $q->group('table_0'));
        $this->assertInstanceOf(Q::class, $q->group(['table_1', 'table_2']));

        $g = $q->group();

        foreach([0, 1, 2] as $c) {
            $this->assertArrayHasKey($c, $g);

            $this->assertEquals('table_' . $c, $g[$c]);
        }

        $q = Q::factory();

        $this->assertInstanceOf(Q::class, $q->order('table_0', 'asc'));
        $this->assertInstanceOf(Q::class, $q->order(['table_1' => 'desc', 'table_2' => 'asc']));

        $o = $q->order();

        foreach(['table_0' => 'asc', 'table_1' => 'desc', 'table_2' => 'asc'] as $c => $v) {
            $this->assertArrayHasKey($c, $o);

            $this->assertEquals($o[$c], $v);
        }
    }

    public function testLimitOffset()
    {
        $q = Q::factory();

        $this->assertInstanceOf(Q::class, $q->limit('2'));
        $this->assertEquals('2', $q->limit());

        $this->assertInstanceOf(Q::class, $q->limit('4', 24));
        $this->assertEquals('4', $q->limit());
        $this->assertEquals(24, $q->offset());

        $this->assertInstanceOf(Q::class, $q->offset(48));
        $this->assertEquals(48, $q->offset());
    }

    public function testRender()
    {
        $q = Q::factory()->select()->from('table_0', '0');
        $this->assertEquals('SELECT * FROM table_0 AS 0', $q->__toString());

        // $q = Q::factory()->insert('table_1');
        // $this->assertEquals('INSERT INTO table_1 () VALUES ()', $q->__toString());

        $q = Q::factory()->update('table_0');
        $this->assertEquals('UPDATE table_0', $q->__toString());

        $q = Q::factory()->delete()->from('table_0', '0');
        $this->assertEquals('DELETE FROM table_0 AS 0', $q->__toString());
    }
}
