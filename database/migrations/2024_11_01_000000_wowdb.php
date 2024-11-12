<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Wowdb;

return new class extends Migration
{
    const DB_CONNECTION = 'wowdb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Realm::TABLENAME,      function (Blueprint $table) { Wowdb\Realm::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Race::TABLENAME,       function (Blueprint $table) { Wowdb\Race::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Zone::TABLENAME,       function (Blueprint $table) { Wowdb\Zone::tableBlueprint($table); } );

        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Klass::TABLENAME,      function (Blueprint $table) { Wowdb\Klass::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Klass\Spec::TABLENAME, function (Blueprint $table) { Wowdb\Klass\Spec::tableBlueprint($table); } );

        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Item::TABLENAME,       function (Blueprint $table) { Wowdb\Item::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Item\Buy::TABLENAME,   function (Blueprint $table) { Wowdb\Item\Buy::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Item\Create::TABLENAME, function (Blueprint $table) { Wowdb\Item\Create::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Item\Drop::TABLENAME,  function (Blueprint $table) { Wowdb\Item\Drop::tableBlueprint($table); } );
        Schema::connection(self::DB_CONNECTION)
            ->create(Wowdb\Item\Quest::TABLENAME, function (Blueprint $table) { Wowdb\Item\Quest::tableBlueprint($table); } );

        Schema::connection(self::DB_CONNECTION)->create('race_klasses', function (Blueprint $table) {
            $table->unsignedBigInteger('race_id');
            $table->unsignedBigInteger('klass_id');
            $table->unique(['race_id','klass_id']);

            $table->foreign('race_id')->references('id')->on('races')->onDelete('cascade');
            $table->foreign('klass_id')->references('id')->on('klasses')->onDelete('cascade');
        });

        Schema::connection(self::DB_CONNECTION)->create('item_races', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('race_id');
            $table->unique(['item_id', 'race_id']);

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('race_id')->references('id')->on('races')->onDelete('cascade');
        });

        Schema::connection(self::DB_CONNECTION)->create('item_klasses', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('klass_id');
            $table->unique(['item_id', 'klass_id']);

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('klass_id')->references('id')->on('klasses')->onDelete('cascade');
        });

        Schema::connection(self::DB_CONNECTION)->create('item_quests', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('quest_id');
            $table->unique(['item_id', 'quest_id']);

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('quest_id')->references('id')->on('quests')->onDelete('cascade');
        });

        Schema::connection(self::DB_CONNECTION)->create('item_buys', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('buy_id');
            $table->unique(['item_id', 'buy_id']);

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('buy_id')->references('id')->on('buys')->onDelete('cascade');
        });

        // item created_by links
        Schema::connection(self::DB_CONNECTION)->create('item_creates', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('create_id');
            $table->unique(['create_id', 'item_id']);

            $table->foreign('create_id')->references('id')->on('creates')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });

        // created_by recipe links
        Schema::connection(self::DB_CONNECTION)->create('create_items', function (Blueprint $table) {
            $table->unsignedBigInteger('create_id');
            $table->unsignedBigInteger('item_id');
            $table->unique(['create_id', 'item_id']);

            $table->foreign('create_id')->references('id')->on('creates')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Realm::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Race::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Zone::TABLENAME);

        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Klass::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Klass\Spec::TABLENAME);

        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Item::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Item\Buy::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Item\Create::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Item\Drop::TABLENAME);
        Schema::connection(self::DB_CONNECTION)->dropIfExists(Wowdb\Item\Quest::TABLENAME);

        Schema::connection(self::DB_CONNECTION)->dropIfExists('race_klasses');
        Schema::connection(self::DB_CONNECTION)->dropIfExists('item_races');
        Schema::connection(self::DB_CONNECTION)->dropIfExists('item_klasses');
        Schema::connection(self::DB_CONNECTION)->dropIfExists('item_creates');
        Schema::connection(self::DB_CONNECTION)->dropIfExists('item_quests');
        Schema::connection(self::DB_CONNECTION)->dropIfExists('item_buys');
        Schema::connection(self::DB_CONNECTION)->dropIfExists('create_items');
    }
};
