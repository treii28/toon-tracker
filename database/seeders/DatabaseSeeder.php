<?php

namespace Database\Seeders;

use App\Models\Classification;
use App\Models\Item;
use App\Models\Need;
use App\Models\Toon;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Scott Webster Wood',
            'email' => 'treii28@gmail.com',
            'password' => '$2y$12$Klve23PXNBtICsPB327fxO02sTHF6B4AIKuYlMmkmtkGSAhNZK3WG'
        ]);
        Classification::dbSeed();
        $toonjson = file_get_contents(database_path('seeders/toons.json'));
        $toondata = json_decode($toonjson, true);
        Toon::dbSeed($toondata);
        $itemjson = file_get_contents(database_path('seeders/items.json'));
        $itemdata = json_decode($itemjson, true);
        Item::dbSeed($itemdata);
        $needjson = file_get_contents(database_path('seeders/needs.json'));
        $needdata = json_decode($needjson, true);
        Need::dbSeed($needdata);
    }
}
