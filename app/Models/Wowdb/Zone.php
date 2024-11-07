<?php

namespace App\Models\Wowdb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;

class Zone extends Model
{
    //use HasFactory;

    const CATEGORIES = [
        'World',
        'Continent',
        'Zone',
        'SubZone',
        'Capital City',
        'Dungeon',
        'Raid',
        'Battleground',
        'Arena'
    ];

    const TERRITORIES = [
        'Alliance',
        'Horde',
        'Contested',
        'PvP'
    ];

    //const EXPANSIONS = ['Classic', 'Burning Crusade', 'Wrath of the Lich King', 'Cataclysm', 'Mists of Pandaria', 'Warlords of Draenor', 'Legion', 'Battle for Azeroth', 'Shadowlands'];

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "zone";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'name',
        'category',
        'level_min', 'level_max',
        'parent_id',
        'territory',
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
        $table->string('name', 64);
        $table->enum('category', self::CATEGORIES);
        $table->unsignedInteger('level_min')->nullable();
        $table->unsignedInteger('level_max')->nullable();
        $table->unsignedBigInteger('parent_id')->nullable();
        $table->enum('territory', self::TERRITORIES);
        //$table->enum('expansion', self::EXPANSIONS);
        $table->timestamps();
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function parent(): BelongsTo { return $this->belongsTo(Zone::class); }
    public function hasParent(): bool { return ($this->parent()->count() > 0); }

    public function children(): hasMany { return $this->hasMany(Zone::class, 'parent_id'); }
    public function hasChildren(): bool { return ($this->children()->count() > 0); }

    public function drops(): hasMany { return $this->hasMany(Item\Drop::class); }
    public function hasDrops(): bool { return ($this->drops()->count() > 0); }
}
