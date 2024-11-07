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
        Schema::create(\App\Models\Toon::TABLENAME,           function(Blueprint $table) { \App\Models\Toon::tableBlueprint($table); } );
        Schema::create(\App\Models\Need::TABLENAME,           function(Blueprint $table) { \App\Models\Need::tableBlueprint($table); } );
        Schema::create(\App\Models\Classification::TABLENAME, function(Blueprint $table) { \App\Models\Classification::tableBlueprint($table); } );
        Schema::create(\App\Models\Item::TABLENAME,           function(Blueprint $table) { \App\Models\Item::tableBlueprint($table); } );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(\App\Models\Toon::TABLENAME);
        Schema::dropIfExists(\App\Models\Need::TABLENAME);
        Schema::dropIfExists(\App\Models\Classification::TABLENAME);
        Schema::dropIfExists(\App\Models\Item::TABLENAME);
    }
};
