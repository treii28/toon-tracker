<?php

namespace Database\Seeders;

use App\Models\Classification;
use App\Models\Item;
use App\Models\Need;
use App\Models\Toon;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->firstOrCreate([
            'name' => 'Scott Webster Wood',
            'email' => 'treii28@gmail.com',
            'password' => '$2y$12$Klve23PXNBtICsPB327fxO02sTHF6B4AIKuYlMmkmtkGSAhNZK3WG'
        ]);

        Classification::dbSeed();

        $this->importSeeds();

        /*
        $toonjson = file_get_contents(database_path('seeders/toons.json'));
        $toondata = json_decode($toonjson, true);
        Toon::dbSeed($toondata);
        $itemjson = file_get_contents(database_path('seeders/items.json'));
        $itemdata = json_decode($itemjson, true);
        Item::dbSeed($itemdata);
        $needjson = file_get_contents(database_path('seeders/needs.json'));
        $needdata = json_decode($needjson, true);
        Need::dbSeed($needdata);
        */
    }

    private function importSeeds(): void
    {
        $this->disableForeignKeyChecks();
        static::importToons();
        static::importItems();
        static::importNeeds();
        $this->enableForeignKeyChecks();
    }
    private function importToons(): void
    {
        $toonjson = file_get_contents(database_path('seeders/exports/toons.json'));
        $toondata = json_decode($toonjson, true);
        $woodId = User::where('email', 'treii28@gmail.com')->first()->id;
        foreach($toondata as $toon) {
            $toon['user_id'] = $woodId;
            Toon::create($toon);
        }
    }
    private function importItems(): void
    {
        $itemjson = file_get_contents(database_path('seeders/exports/items.json'));
        $itemdata = json_decode($itemjson, true);
        foreach($itemdata as $item) {
            Item::create($item);
        }
    }
    private function importNeeds(): void
    {
        $needjson = file_get_contents(database_path('seeders/exports/needs.json'));
        $needdata = json_decode($needjson, true);
        foreach($needdata as $need) {
            Need::create($need);
        }
    }

    private function disableForeignKeyChecks(): void
    {
        $driverName = DB::getDriverName();
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } else {
            Schema::disableForeignKeyConstraints();
        }
    }

    private function enableForeignKeyChecks(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }
}
