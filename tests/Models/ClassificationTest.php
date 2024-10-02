<?php

namespace Tests\Models;

use App\Models\Classification;
use Tests\TestCase;


class ClassificationTest extends TestCase
{
    public function testPublic()
    {
        $races = Classification::getRaceList();
        $this->assertIsArray($races);
    }
}
