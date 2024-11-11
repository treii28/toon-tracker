<?php

namespace Tests\Models\Wowdb;

use App\Models\Wowdb\Item;
use Tests\TestCase;
use function Laravel\Prompts\progress;

class ItemTest extends TestCase
{
    public function testData()
    {
        $itemdata = json_decode(file_get_contents(download_path('data.json')), true);
        $itemcount = count($itemdata);
        $itemkeys = [];
        $createdkeys = [];
        $sourcekeys = [];
        $categories = [];
        $labels = [];
        $counter = 0;
        foreach($itemdata as $item) {
            echo progress_bar($counter++, $itemcount, "Scanning items", 70) . PHP_EOL;
            foreach($item as $key => $value)
                if(!in_array($key, $itemkeys))
                    $itemkeys[] = $key;
            if(array_key_exists('created_at', $item)) {
                foreach($item['created_at'] as $key => $value)
                    if(!in_array($key, $createdkeys))
                        $createdkeys[] = $key;
            }
            if(array_key_exists('tooltip', $item)) {
                foreach($item['tooltip'] as $tooltip)
                    if(array_key_exists('label', $tooltip) && !in_array($tooltip['label'], $labels))
                        $labels[] = $tooltip['label'];
            }
            if(array_key_exists('source', $item)) {
                foreach($item['source'] as $key => $value)
                    if(!in_array($key, $sourcekeys))
                        $sourcekeys[] = $key;
                if(array_key_exists('category', $item['source']) && !in_array($item['source']['category'], $categories))
                    $categories[] = $item['source']['category'];
            }
        }
        $this->assertIsArray($itemkeys);
    }
}
