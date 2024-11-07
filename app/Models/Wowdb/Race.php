<?php

namespace App\Models\Wowdb;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Wowdb;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Race extends Model
{
    //use HasFactory;

    const RACES = [
        'Human',
        'Dwarf',
        'Night Elf',
        'Gnome',
        'Orc',
        'Undead',
        'Tauren',
        'Troll',
        /* exp packs
        'Draenei',
        'Worgen',
        'Blood Elf',
        'Goblin',
        'Pandaren',
        'Highmountain Tauren',
        'Nightborne',
        'Void Elf',
        'Lightforged Draenei',
        'Zandalari Troll',
        'Kul Tiran',
        'Dark Iron Dwarf',
        'Vulpera',
        'Mechagnome',
        'Mag\'har Orc'
        */
    ];
    const FACTIONS = ['Alliance', 'Horde'];

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "race";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'name',
        'klass',
        'faction',
        'spec_id',
        'created_at',
        'updated_at'
    ];

    /**
     * @var string $table
     */
    protected $table = self::TABLENAME;

    public static function getTableName(): string { return self::TABLENAME; }

    public static function tableBlueprint(Blueprint $table): void
    {
        $table->id();
        $table->enum('name', self::RACES);
        $table->string('klass', 32);
        $table->enum('faction', self::FACTIONS);
        $table->unsignedBigInteger('spec_id');
        //$table->unsignedBigInteger('startzone_id')->nullable(true);
        //$table->unsignedBigInteger('capital_id')->nullable(true);
        $table->timestamps();

        $table->foreign('spec_id')->references('id')->on('klass_specs');
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function spec(): BelongsTo { return $this->belongsTo(Wowdb\Klass\Spec::class); }

    public function klass() {
        return $this->spec->klass;
    }
}
