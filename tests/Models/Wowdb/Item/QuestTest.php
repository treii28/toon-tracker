<?php

namespace Tests\Models\Wowdb\Item;

use App\Models\Wowdb\Item\Quest;
use Tests\TestCase;

class QuestTest extends TestCase
{
    public function testInstantiation()
    {
        $quest = new Quest([
            "questId" => 6,
            "name" => "Bounty on Garrick Padfoot",
            "faction" => "Alliance"
        ]);
        $this->assertInstanceOf(Quest::class, $quest);
        $this->assertEquals(6, $quest->id);
    }
}
