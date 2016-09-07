<?php

namespace Panda\Tests\Essence;

use Panda\Essence\Swimmer;

class SwimmerTest extends \PHPUnit\Framework\TestCase
{
    public function testPassNextPrevent()
    {
        $swimmer = new Swimmer();

        $this->assertEquals($swimmer->passed(), false);

        $this->assertInstanceOf(Swimmer::class, $swimmer->pass());

        $this->assertEquals($swimmer->passed(), true);

        $this->assertInstanceOf(Swimmer::class, $swimmer->prevent());

        $this->assertEquals($swimmer->passed(), false);

        $this->assertInstanceOf(Swimmer::class, $swimmer->next());

        $this->assertEquals($swimmer->passed(), true);
    }
}
