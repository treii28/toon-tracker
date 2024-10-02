<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(\App\Models\Toon::getTableName(),        function(Blueprint $table) { \App\Models\Toon::getTableBlueprint($table); } );
        Schema::create(\App\Models\Need::getTableName(),        function(Blueprint $table) { \App\Models\Need::getTableBlueprint($table); } );
        Schema::create(\App\Models\Classification::getTableName(), function(Blueprint $table) { \App\Models\Classification::getTableBlueprint($table); } );
        Schema::create(\App\Models\Item::getTableName(), function(Blueprint $table) { \App\Models\Item::getTableBlueprint($table); } );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(\App\Models\Toon::getTableName());
        Schema::dropIfExists(\App\Models\Need::getTableName());
        Schema::dropIfExists(\App\Models\Classification::getTableName());
        Schema::dropIfExists(\App\Models\Item::getTableName());
    }
};
