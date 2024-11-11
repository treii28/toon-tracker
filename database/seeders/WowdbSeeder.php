<?php

namespace Database\Seeders;


use App\Models\Wowdb\Item;
use App\Models\Wowdb\Race;
use App\Models\Wowdb\Realm;
use App\Models\Wowdb\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Wowdb\Klass;
use App\Models\Wowdb\Klass\Spec;

class WowdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('wowdb')->statement('PRAGMA foreign_keys = OFF');

        $this->seedRealms();
        $this->seedZones();
        $this->seedKlassesAndSpecs();
        $this->seedRaces();
        $this->seedItems();

        DB::connection('wowdb')->statement('PRAGMA foreign_keys = ON');
    }

    private function seedItems(): void
    {
        $itemdata = json_decode(file_get_contents(download_path('data.json')), true);
        $itemcount = count($itemdata);
        $counter = 0;
        foreach($itemdata as $item) {
            echo progress_bar($counter++, $itemcount, "Seeding Items");
            if ($itemObj = Item::where('id', $item['itemId'])->first()) {
                unset($item['itemId']);
                $itemObj->update($item);
            } else {
                Item::firstOrCreate($item);
            }
        }
        echo PHP_EOL;
    }

    private function seedZones(): void
    {
        $zonedata = json_decode(file_get_contents(download_path('zones.json')), true);
        $zonecount = count($zonedata);
        $counter = 0;
        foreach($zonedata as $zone) {
            echo progress_bar($counter++, $zonecount, "Seeding Zones");
            $data = [
                'id' => $zone['id'],
                'name' => $zone['name'],
                'category' => $zone['category'],
                'level_min' => $zone['level'][0],
                'level_max' => $zone['level'][1],
                'territory' => $zone['territory'],
            ];
            if(array_key_exists('parent_id', $zone))
                $data['parent_id'] = $zone['parent_id'];

            Zone::firstOrCreate($data);
        }
        echo PHP_EOL;
    }
    private function seedRealms(): void
    {
        $realmdata = json_decode(file_get_contents(download_path('realms.json')), true);
        $realmcount = count($realmdata);
        $counter = 0;
        foreach($realmdata as $battlegroup => $realms) {
            echo progress_bar($counter++, $realmcount, "Seeding Realms");
            foreach($realms as $realm) {
                $data = [
                    'name' => $realm,
                    'battlegroup' => $battlegroup,
                    'universe' => "Classic ERA",
                ];
                Realm::firstOrCreate($data);
            }
        }
        echo PHP_EOL;
    }

    private function seedKlassesAndSpecs(): void
    {
        $klassdata = json_decode(file_get_contents(download_path('klasses.json')), true);
        $klasscount = count($klassdata);
        $counter = 0;
        foreach ($klassdata as $klass) {
            echo progress_bar($counter++, $klasscount, "Seeding Klasses");
            $specs = [];
            if(array_key_exists('specs', $klass)) {
                $specs = $klass['specs'];
                unset($klass['specs']);
            }

            $klassRecord = Klass::firstOrCreate($klass);

            foreach ($specs as $spec) {
                $spec['klass_id'] = $klassRecord->id;
                $klassRecord->specs()->firstOrCreate($spec);
            }
        }
        echo PHP_EOL;
    }

    private function seedRaces(): void
    {
        $racedata = json_decode(file_get_contents(download_path('races.json')), true);
        $racecount = count($racedata);
        $counter = 0;
        foreach($racedata as $race) {
            echo progress_bar($counter++, $racecount, "Seeding races");
            $klasses = [];
            if(array_key_exists('klasses', $race)) {
                $klasses = $race['klasses'];
                unset($race['klasses']);
            }
            $raceRecord = Race::firstOrCreate($race);
            foreach($klasses as $klassName) {
                $klassRecord = Klass::where('name', $klassName)->first();
                if($klassRecord && !$raceRecord->klasses()->where('klass_id', $klassRecord->id)->exists())
                    $raceRecord->klasses()->attach($klassRecord);
            }
        }
        echo PHP_EOL;
    }
}
