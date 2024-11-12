<?php

namespace App\Models\Wowdb\Item;

use App\Models\Wowdb\Item;
use App\Models\Wowdb\Race;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Buy extends Model
{
    //use HasFactory;

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "buy";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'name',
        'faction',
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

        $table->string('name', 128);
        $table->enum('faction', Race::FACTIONS)->default('Both');

        $table->timestamps();
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function items(): BelongsToMany
    {
        return $this->BelongsToMany(Item::class, 'item_buys', 'buy_id', 'item_id');
    }
}

