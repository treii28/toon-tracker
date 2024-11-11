<?php

namespace App\Models\Wowdb\Item;

use App\Models\Wowdb\Item;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Create extends Model
{
    //use HasFactory;

    const CATEGORIES = [
        "Alchemy",
        "Blacksmithing",
        "Cooking",
        "Enchanting",
        "Engineering",
        "First Aid",
        "Inscription",
        "Jewelcrafting",
        "Leatherworking",
        "Mining",
        "Tailoring"
    ];

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "create";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'amount_min', 'amount_max', 'amount', // last is alias mutator
        'requiredSkill',
        'category',
        'reagents',
        'recipes',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'reagents' => AsArrayObject::class,
        'recipes'  => AsArrayObject::class
    ];

    protected static function boot()
    {
        parent::boot();

        // attach recipes after the Create record is saved
        static::saved(function(Create $create): void {
            if(!empty($create->recipes))
                foreach($create->recipes as $itemId)
                    if (!$create->recipe_items()->where('item_id', $itemId)->exists())
                        $create->recipe_items()->attach($itemId);
        });
    }

    /**
     * @var string $table
     */
    protected $table = self::TABLENAME;

    public static function getTableName(): string { return self::TABLENAME; }

    public static function tableBlueprint(Blueprint $table): void
    {
        $table->id();

        $table->unsignedInteger('amount_min')->nullable(true);
        $table->unsignedInteger('amount_max')->nullable(true);
        $table->unsignedInteger('requiredSkill')->nullable(true);
        $table->enum('category', self::CATEGORIES);
        $table->text('reagents')->nullable(true);
        $table->text('recipes')->nullable(true);

        $table->timestamps();
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function setAmountAttribute(array $values): void
    {
        $this->attributes['amount_min'] = $values[0];
        $this->attributes['amount_max'] = $values[1];
    }

    public function getAmountAttribute(): array {
        return [
            $this->attributes['amount_min'],
            $this->attributes['amount_max']
        ];
    }

    public function recipe_items()
    {
        return $this->belongsToMany(Item::class, 'create_items', 'create_id', 'item_id');
    }
}
