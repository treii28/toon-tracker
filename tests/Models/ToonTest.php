<?php

namespace Tests\Models;

use App\Models\Toon;
use Tests\TestCase;

class ToonTest extends TestCase
{
    public function testPublic()
    {
        $needToons = Toon::ToonsWithNeeds();
        $toon = Toon::where('name', 'like', 'Lilmissd%')->first();
        $this->assertIsObject($toon);
        $this->assertInstanceOf(Toon::class, $toon);
        $this->assertEquals('Lilmissd', $toon->name);
        $classification = $toon->classification();
        $this->assertIsObject($classification);
        $this->assertInstanceOf(\App\Models\Classification::class, $classification);
        $this->assertEquals('Alliance', $classification->faction);
        $this->assertEquals('Gnome', $classification->race);
        $this->assertEquals('Warlock', $classification->class);
        $this->assertEquals('Affliction', $toon->spec);

    }
}
