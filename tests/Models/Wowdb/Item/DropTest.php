<?php

namespace Tests\Models\Wowdb\Item;

use App\Models\Wowdb\Item\Drop;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Tests\TestCase;

class DropTest extends TestCase
{
    public function testMutators()
    {
        $drop = new Drop();
        $this->assertInstanceOf(Drop::class, $drop);
        $drop->boss = "'General Drakkisath','Onyxia'";
        $boss = $drop->boss;
        $foo = $drop->getAttribute('boss')->getArrayCopy();
        $this->assertEquals('General Drakkisath', $boss[0]);
        $this->assertIsArray($drop->boss);
    }
}
