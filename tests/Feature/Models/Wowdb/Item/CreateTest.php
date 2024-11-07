<?php

namespace Models\Wowdb\Item;

use App\Models\Wowdb\Item\Create;
use Tests\TestCase;

class CreateTest extends TestCase
{
    public function testInstantiation()
    {
        $create = new Create([
            "amount" => [1,2],
            "requiredSkill" => 1,
            "category" => "Alchemy",
            "reagents" => [
                [
                    "itemId" => 723,
                    "amount" => 1
                ]
            ],
            //"recipes" => [ 2697 ];
        ]);
        $this->assertInstanceOf(Create::class, $create);
        $amount = $create->getAttribute('amount');
        $this->assertIsArray($amount);
        $this->assertCount(2, $amount);
        $attributes = $create->getAttributes();
        $this->assertIsString($attributes['reagents']);
        $this->assertJson($attributes['reagents']);
        $this->assertEquals(1, $create->amount_min);
        $this->assertEquals(2, $create->amount_max);
        $this->assertEquals(1, $create->requiredSkill);
    }
}
