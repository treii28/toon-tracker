<?php

namespace App\Models\Wowdb\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

use App\Models\Wowdb\Race;

class Quest extends Model
{
    //use HasFactory;

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "quest";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id', 'questId',
        'name',
        'klass',
        'faction',
        'spec_id',
        'created_at',
        'updated_at'
    ];

    function getQuestIdAttribute(): int { return $this->attributes['id']; }
    function setQuestIdAttribute(int $value): void { $this->attributes['id'] = $value; }

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

    // todo: does this need klass restrictions?

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;
}
