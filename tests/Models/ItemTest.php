<?php

namespace Tests\Models;

use App\Models\Item;
use Tests\TestCase;

class ItemTest extends TestCase
{
    public function testItem()
    {
        $ixml = Item::getWowheadXml(22433);
        $ijson = json_encode($ixml, JSON_PRETTY_PRINT);
        $zonelist = Item::getZoneList();
        $instances = Item::getInstanceList();
        $data = file_get_contents(database_path('seeders/items.json'));
        $json = json_decode($data, true);
        $this->assertIsArray($json);
    }
}
