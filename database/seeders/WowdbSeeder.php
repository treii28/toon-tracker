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
        $itemTotal = count($itemdata);
        $counter = 0;
        $created = 0;
        $updated = 0;
        foreach($itemdata as $item) {
            echo progress_bar($counter++, $itemTotal, "Seeding Items");
            if ($itemObj = Item::where('id', $item['itemId'])->first()) {
                unset($item['itemId']);
                $updated++;
                $itemObj->update($item);
            } else {
                $created++;
                Item::firstOrCreate($item);
            }
        }
        echo PHP_EOL;
        echo "created: $created, updated: $updated" . PHP_EOL;
    }

    private function seedZones(): void
    {
        $zonedata = json_decode(file_get_contents(download_path('zones.json')), true);
        foreach($zonedata as $zone) {
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
    }
    private function seedRealms(): void
    {
        $realmdata = json_decode(file_get_contents(download_path('realms.json')), true);
        foreach($realmdata as $battlegroup => $realms) {
            foreach($realms as $realm) {
                $data = [
                    'name' => $realm,
                    'battlegroup' => $battlegroup,
                    'universe' => "Classic ERA",
                ];
                Realm::firstOrCreate($data);
            }
        }
    }

    private function seedKlassesAndSpecs(): void
    {
        $klassdata = json_decode(file_get_contents(download_path('klasses.json')), true);
        foreach ($klassdata as $klass) {
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
    }

    private function seedRaces(): void
    {
        $racedata = json_decode(file_get_contents(download_path('races.json')), true);
        foreach($racedata as $race) {
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
    }
}
