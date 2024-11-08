<?php

namespace App\Models\Wowdb;

use App\Models\Wowdb;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Item extends Model
{
    //use HasFactory;

    const CLASSES = [
        "Armor",
        "Consumable",
        "Container",
        "Gem",
        "Glyph",
        "Key",
        "Miscellaneous",
        "Money",
        "Projectile",
        "Quest",
        "Quiver",
        "Reagent",
        "Recipe",
        "Trade Goods",
        "Weapon"
    ];

    const SUBCLASSES = [
        "Alchemy",
        "Ammo Pouch",
        "Armor Enchantment",
        "Arrow",
        "Axe",
        "Bag",
        "Bandage",
        "Blacksmithing",
        "Blue",
        "Book",
        "Bow",
        "Bullet",
        "Cloth",
        "Consumable",
        "Cooking",
        "Crossbow",
        "Dagger",
        "Death Knight",
        "Devices",
        "Druid",
        "Elemental",
        "Elixir",
        "Enchanting Bag",
        "Enchanting",
        "Engineering Bag",
        "Engineering",
        "Exotic",
        "Explosives",
        "First Aid",
        "Fishing Pole",
        "Fishing",
        "Fist Weapon",
        "Flask",
        "Food & Drink",
        "Gem Bag",
        "Green",
        "Gun",
        "Herb Bag",
        "Herb",
        "Holiday",
        "Hunter",
        "Idol",
        "Inscription Bag",
        "Item Enhancement",
        "Jewelcrafting",
        "Junk",
        "Key",
        "Leather",
        "Leatherworking Bag",
        "Leatherworking",
        "Libram",
        "Mace",
        "Mage",
        "Mail",
        "Materials",
        "Meat",
        "Meta",
        "Metal & Stone",
        "Mining Bag",
        "Miscellaneous",
        "Money(OBSOLETE)",
        "Mount",
        "Orange",
        "Other",
        "Paladin",
        "Parts",
        "Pet",
        "Plate",
        "Polearm",
        "Potion",
        "Priest",
        "Prismatic",
        "Purple",
        "Quest",
        "Quiver",
        "Reagent",
        "Red",
        "Rogue",
        "Scroll",
        "Shaman",
        "Shield",
        "Sigil",
        "Simple",
        "Soul Bag",
        "Staff",
        "Sword",
        "Tailoring",
        "Thrown",
        "Totem",
        "Wand",
        "Warlock",
        "Warrior",
        "Weapon Enchantment",
        "Yellow"
    ];

    const QUALITIES = [
        'Poor',
        'Common',
        'Uncommon',
        'Rare',
        'Epic',
        'Legendary',
        'Heirloom'
    ];

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "item";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'name',
        'uniqueName',
        'icon',
        'class',
        'subclass',
        'sellPrice',
        'quality',
        'itemLevel',
        'requiredLevel',
        'slot',
        'tooltip',
        'itemLink',
        'cost',
        'vendorPrice',
        'contentPhase',
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
        $table->string('uniqueName', 128);
        $table->string('icon', 128)->nullable(true);
        $table->enum('class', self::CLASSES)->nullable(true);
        $table->enum('subclass', self::SUBCLASSES)->nullable(true);
        $table->unsignedInteger('sellPrice')->nullable(true);
        $table->enum('quality', self::QUALITIES)->nullable(true);
        $table->unsignedInteger('itemLevel')->nullable(true);
        $table->unsignedInteger('requiredLevel')->nullable(true);
        $table->enum('slot', [
            "Ammo",
            "Back",
            "Bag",
            "Chest",
            "Feet",
            "Finger",
            "Hands",
            "Head",
            "Held In Off-hand",
            "Legs",
            "Main Hand",
            "Neck",
            "Non-equippable",
            "Off Hand",
            "One-Hand",
            "Ranged",
            "Relic",
            "Shirt",
            "Shoulder",
            "Tabard",
            "Thrown",
            "Trinket",
            "Two-Hand",
            "Waist",
            "Wrist"
        ]);
        $table->text('tooltip')->nullable(true);
        $table->text('source')->nullable(true);
        $table->text('createdBy')->nullable(true);
        $table->string('itemLink', 128)->nullable(true);
        $table->unsignedInteger('cost')->nullable(true);
        $table->unsignedInteger('vendorPrice')->nullable(true);
        $table->unsignedInteger('contentPhase')->nullable(true);
        $table->enum('faction', ['Alliance', 'Horde', 'Both'])->default('Both');

        $table->timestamps();

    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tooltip' => AsArrayObject::class,
        'createdBy' => AsArrayObject::class,
        'source' => AsArrayObject::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if(!empty($item->tooltip))
                $item->parseTooltip();
            if(!empty($item->createdBy))
                $item->parseCreatedBy();
            if(!empty($item->source))
                $item->parseSource();
        });
    }

    /**
     * Determine if a list of tooltips contains a given label (all should)
     *
     * @param string $label
     * @return bool
     */
    public function tooltipIncludesLabel(string $label): bool {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && $tt['label'] === $label) return true;
        return false;
    }

    /**
     * Determine if an item includes a 'Unique' label designating a unique item
     *
     * @return bool
     */
    public function isUnique(): bool { return $this->tooltipIncludesLabel('Unique'); }

    /**
     * Determine if an item includes a 'Binds when equipped' label designating a bind-on-equip item
     *
     * @return bool
     */
    public function isBindOnEquip(): bool { return $this->tooltipIncludesLabel('Binds when equipped'); }

    /**
     * Determine if an item includes a 'Binds when picked up' label designating a bind-on-pickup item
     *
     * @return bool
     */
    public function isBindOnPickup(): bool { return $this->tooltipIncludesLabel('Binds when picked up'); }

    /**
     * Check for labels that start with a given string, returning the remainder of the label if found or null if not
     *
     * @param string $label
     * @return string|null
     */
    public function tooltipStartsWith(string $label): string|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/^".$label."[\: ]*(.*)/", $tt['label'], $m))
                return $m;
        return null;
    }

    public function sourceCategory(): string|null {
        if(($source = $this->source) && is_array($source) && array_key_exists('category', $source))
            return $source['category'];
        return null;
    }

    public function sourceIncludesQuest(): bool { return ($this->sourceCategory() === 'Quest'); }
    public function getSourceQuests(): array|null {
        if(array_key_exists('quests', $this->source))
            return $this->source['quests'];
        return null;
    }

    public function sourceIncludesVendor(): bool { return ($this->sourceCategory() === 'Vendor'); }

    private function parseTooltip(): void {

        // parse class restrictions
        if($klasses = $this->tooltipStartsWith('Classes')) {
            foreach (parse_list($klasses) as $klass) {
                $klassObj = Klass::where('name', $klass)->first();
                if ($klassObj && !$this->klasses->contains($klassObj->id))
                    $this->klasses()->attach($klassObj->id);
            };
        }

        // parse race restrictions
        if($races = $this->tooltipStartsWith('Races')) {
            foreach (parse_list($races) as $race) {
                $raceObj = Race::where('name', $race)->first();
                if ($raceObj && !$this->races->contains($raceObj->id))
                    $this->races()->attach($raceObj->id);
            }
        }

    }
    private function parseCreatedBy(): void {
        // @todo parse createdBy
    }
    private function parseSource(): void {
        if($this->sourceIncludesQuest()) {
            foreach($this->getSourceQuests() as $quest) {
                if(!$this->quests->contains($quest['questId'])) {
                    $questObj = Quest::where('id', $quest['questId'])->first();
                    if($questObj && !$this->quests->contains($questObj->id))
                        $this->quests()->attach($questObj->id);
                    else {
                        if(array_key_exists('questId', $quest)) {
                            $quest['id'] = $quest['questId'];
                            unset($quest['questId']);
                        }
                        $this->quests()->create($quest);
                    }
                }
            }
        }
        // @todo parse source
    }

    /*
     * @todo handle relationships by parsing tooltip, createdby and source
     *   notes:
     *    tooltip array includes information like faction, class, bind-on-equip
     *    source contains list of quests/vendor(s) including faction
     *    quests in source is an array
     *    createdBy array includes single amount array, reagents array, recipe array
     */

    /**
     * Define the one-to-many relationship with Drop.
     */
    public function drops(): HasMany { return $this->hasMany(Drop::class, 'item_id'); }

    /**
     * Define the many-to-many relationship with Race.
     */
    public function races(): BelongsToMany { return $this->belongsToMany(Race::class, 'item_race', 'item_id', 'race_id'); }
    public function hasRaceRestriction(): bool { return ($this->races->count() > 0); }

    /**
     * Define the many-to-many relationship with Klass.
     */
    public function klasses(): BelongsToMany { return $this->belongsToMany(Klass::class, 'item_klass', 'item_id', 'klass_id'); }
    public function hasKlassRestriction(): bool { return ($this->klasses->count() > 0); }

    public function canAllianceUse(): bool { return ($this->faction === 'Alliance' || $this->faction === 'Both'); }
    public function canHordeUse(): bool { return ($this->faction === 'Horde' || $this->faction === 'Both'); }

    public function creates(): BelongsToMany
    {
        return $this->belongsToMany(Item\Create::class, 'create_item', 'item_id', 'create_id');
    }
}
