<?php

namespace App\Models\Wowdb\Item;

use App\Models\Wowdb\Zone;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drop extends Model
{
    //use HasFactory;

    const CATEGORIES = [
        "Boss Drop",
        "Rare Drop",
        "Zone Drop"
    ];

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "drop";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'npc',
        'category',
        'zone_id',
        'dropChance',
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
        $table->unsignedBigInteger('item_id');
        $table->text('npc')->nullable(true);
        $table->enum('category', self::CATEGORIES);
        $table->unsignedBigInteger('zone_id')->nullable(true);
        $table->decimal('dropChance')->nullable(true);
        $table->timestamps();

        $table->foreign('zone_id')->references('id')->on('zones');
        $table->foreign('item_id')->references('id')->on('items');
    }

    protected function casts()
    {
        return [
            'npc' => AsArrayObject::class,
        ];
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function setNpcAttribute(string|array|object $value=null): void {
        if($value === null)
            $this->attributes['npc'] = null;
        elseif(is_string($value)) { // handle various string formats
            if(is_json($value)) // handle json strings
                $this->attributes['npc'] = $value;
            elseif(count(explode(',', $value)) > 1) // handle comma separated lists, stripping extra garbage
                $this->attributes['npc'] = json_encode(parse_list($value, ','));
            else // handle simple string value
                $this->attributes['npc'] = json_encode([$value]);
        } elseif(is_array($value) || is_object($value)) // handle arrays and objects
            //parent::setAttribute('npc', json_encode($value));
            $this->attributes['npc'] = json_encode($value);
        else throw new \Exception("Invalid npc value type: " . gettype($value));
    }

    public function zone(): BelongsTo { return $this->belongsTo('App\Models\Wowdb\Zone'); }
    public function hasZone(): bool { return ($this->zone instanceof Zone); }

    public function item(): BelongsTo { return $this->belongsTo('App\Models\Wowdb\Item'); }
}
