<?php

namespace App\Models\Wowdb;

/*
useful tooltip keys:

\d+ - \d+ Damage
Speed \d+.\d+
(\d+.\d+ damage per second)
Adds \d+.\d+ damage per second
Durability \d+ / \d+
Drop Chance: \d+.\d+%
\d+ [Armor|Block]
+\d+ [stat]
-\d+ [stat]

Unique
Binds when equipped
Binds when picked up.*
\nMount
Summons and dismisses a .* mount.

Use|Equip: .* \(Proc chance: \d+%\)
Use: .* \(\d+ [Sec|Min] Cooldown\),
Equip: .*
Equip: When struck in combat has a \d+% chance of .* \(Proc chance: \d+%\),
Equip: Increases (your )?[stat] by \d+.
Equip: Increases resistance to .* by \d+.( \u00a0.*)?
Chance on hit: .*
Recipe: .*
Max Stack: \d+
<Random enchantment>

(\n*)Requires [restriction(s)]
Use: .*(Requires .*)?
Use: Teaches you how to permanently enchant a [item] to .*(\u00a0Requires a level \d+ or higher item)?
Permanently enchant [item] to .*(\u00a0Requires a level \d+ or higher item)?

Locked\nRequires Lockpicking (\d+)
Requires any [faction] race
Requires [ability] (\d+)
  [Enchanting|Engineering|Blacksmithing|Tailoring|Leatherworking|Jewelcrafting|Inscription|Poisons|First Aid|Cooking|Fishing]
  [Specialization]
  [riding skill]
Requires [mastery]
  Armorsmith|Weaponsmith|Master Hammersmith|Master Swordsmith|Master Axesmith|Master Swordsmith
  Tribal Leatherworking|Elemental Leatherworking|Dragonscale Leatherworking
  Shadoweave Tailoring|Mooncloth Tailoring|Spellfire Tailoring
Requires ([mounttype])? Riding
Requires [faction] - [Exalted|Revered|Honored|Friendly|Neutral|Unfriendly|Hostile|Hated]
Requires [event]
(\n+)?Requires Level \d+(\n+)?
(\n+)?Requires [items/reagents (\(\d+\))? comma separated]
Requires at least \d+ .*(\nRequires at least \d+ .*) // repeating - explode on \n
Requires more .* than .*
Right click to summon and dismiss your .*\u00a0Requires a .* to .*
Summons and dismisses a [mount] (\u00a0Can only be used .*)?(Requires .*)
 */

use App\Models\Wowdb;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\lessThanOrEqual;
use function Symfony\Component\String\s;

class Item extends Model
{
    //use HasFactory;
    protected $connection = 'wowdb';

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

    const SLOTS = [
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
    ];

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "item";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id', 'itemId',
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
        'source',
        'createdBy',
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
        $table->enum('slot', self::SLOTS)->nullable(true);
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

        static::saving(function (Item $item): void {
            if(!empty($item->tooltip))
                $item->parseTooltip();
            if(!empty($item->createdBy))
                $item->parseCreatedBy();
            if(!empty($item->source))
                $item->parseSource();
        });
    }

    public function setItemIdAttribute(int $value): void { $this->attributes['id'] = $value; }
    public function getItemIdAttribute(): int { return $this->attributes['id']; }

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
     * Determine if an item includes a '<Random enchantment>' label designating a randomly enchanted item
     *
     * @return bool
     */
    public function isRandomlyEnchanted(): bool { return $this->tooltipIncludesLabel('<Random enchantment>'); }
    /**
     * Determine if an item includes a 'Unique' label designating a unique item
     *
     * @return bool
     */
    public function isUnique(): bool { return !empty($this->tooltipStartsWith('Unique')); }
    public function isUniqueEquipped(): bool { return !empty($this->tooltipStartsWith('Unique-Equipped')); }

    /**
     * Determine if an item includes a 'Binds when equipped' label designating a bind-on-equip item
     *
     * @return bool
     */
    public function isBindOnEquip(): bool { return !empty($this->tooltipStartsWith('Binds when equipped')); }

    /**
     * Determine if an item includes a 'Binds when picked up' label designating a bind-on-pickup item
     *
     * @return bool
     */
    public function isBindOnPickup(): bool { return !empty($this->tooltipStartsWith('Binds when picked up')); }

    /**
     * Check for labels that start with a given string, returning the remainder of the label if found or null if not
     *
     * @param string $label
     * @return string|null
     */
    public function tooltipStartsWith(string $label): string|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/^".$label."[\: ]*(.*)/", $tt['label'], $m))
                return $m[1];
        return null;
    }

    public function tooltipIncludes(string $regex): array|null {
        $matches = [];
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/".$regex."/", $tt['label'], $m))
                $matches[] = $m;
        return $matches;
    }

    // may also want to do durability, armor/block, add to damage per second, +/- stats
    public function getTooltipSpeed(): float|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/^Speed (\d+.\d+)$/", $tt['label'], $m))
                return floatval($m[1]);
        return null;
    }
    public function getTooltipDropchance(): float|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/\(Drop Chance\: (\d+.\d+)\%/", $tt['label'], $m))
                return floatval($m[1]);
        return null;
    }
    public function getTooltipCooldown(): string|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/\((\d+ [Sec|Min|Hour|Day]) Cooldown\)/", $tt['label'], $m))
                return $m[1];
        return null;
    }
    public function getTooltipMaxStack(): int|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/^Max Stack: (\d+)$/", $tt['label'], $m))
                return intval($m[1]);
        return null;
    }
    public function getTooltipSlots(): int|null {
        foreach($this->tooltip as $tt)
            if(array_key_exists('label', $tt) && preg_match("/(\d+) Slot.* (Bag|Pouch|Quiver)/", $tt['label'], $m))
                return intval($m[1]);
        return null;
    }

    public function getSourceCategory(): string|null {
        if(($this->source instanceof ArrayObject) && $this->source->offsetExists('category'))
            return $this->source->offsetGet('category');
        return null;
    }

    /**
     * Determine if an item's source data include quests
     *
     * @return bool
     */
    public function sourceIncludesQuest(): bool { return ($this->getSourceCategory() === 'Quest'); }

    /**
     * get quest data if it exists in source data
     *
     * @return array|null
     * @see sourceIncludesQuest()
     */
    public function getSourceQuests(): array|null {
        return (($this->source instanceof ArrayObject) && $this->source->offsetExists('quests')) ? $this->source->offsetGet('quests') : null;
    }

    /**
     * Determine if an item's source data include vendors
     *
     * @return bool
     */
    public function sourceIncludesVendor(): bool { return ($this->getSourceCategory() === 'Vendor'); }

    public function getVendorName(): string|null {
        if($this->sourceIncludesVendor()) {
            if($this->source->offsetExists('name'))
                return $this->source->offsetGet('name');
        }
        return null;
    }
    public function getVendorFaction(): string|null {
        if($this->sourceIncludesVendor()) {
            if($this->source->offsetExists('faction'))
                return $this->source->offsetGet('faction');
        }
        return null;
    }

    public function getVendorCost(): int {
        if($this->sourceIncludesVendor()) {
            if($this->source->offsetExists('cost'))
                return intval($this->source->offsetGet('cost'));
        }
        return 0;
    }

    public function sourceIncludesDrop(): string|null {
        if(preg_match('/\: "(\w+ Drop)",$/', $this->getSourceCategory(), $m))
            return $m[1];
        else
            return null;
    }

    protected function parseTooltip(): void {

        // parse class restrictions
        if($klasses = $this->tooltipStartsWith('Classes')) {
            foreach (parse_list($klasses) as $klass) {
                $klassObj = Klass::where('name', $klass)->first();
                if ($klassObj && !$this->klasses()->where('klass_id', $klassObj->id)->exists())
                    $this->klasses()->attach($klassObj->id);
            };
        }

        // parse race restrictions
        if($races = $this->tooltipStartsWith('Races')) {
            foreach (parse_list($races) as $race) {
                $raceObj = Race::where('name', $race)->first();
                if ($raceObj && !$this->races()->where('race_id', $raceObj->id)->exists())
                    $this->races()->attach($raceObj->id);
            }
        }

    }
    protected function parseCreatedBy(): void {
        // @todo parse createdBy
        $data = [];
        $itemdata = json_decode(file_get_contents(download_path('data.json')), true);
        $createdBys = $this->createdBy->toArray();
        foreach($createdBys as $createdBy) {
            if(array_key_exists('amount', $createdBy) && is_array($createdBy['amount'])) {
                $data['amount_min'] = intval($createdBy['amount'][0]);
                $data['amount_max'] = intval($createdBy['amount'][1]);
            }
            if(array_key_exists('requiredSkill', $createdBy) && !empty($createdBy['requiredSkill']))
                $data['requiredSkill'] = intval($createdBy['requiredSkill']);
            if(array_key_exists('category', $createdBy) && !empty($createdBy['category']))
                $data['category'] = $createdBy['category'];
            if(array_key_exists('reagents', $createdBy) && is_array($createdBy['reagents']))
                $data['reagents'] = $createdBy['reagents'];
            if(array_key_exists('recipes', $createdBy) && !empty($createdBy['recipes']))
                $data['recipes'] = $createdBy['recipes'];

            if($create = Wowdb\Item\Create::where($data)->exists()) {
                $create = Wowdb\Item\Create::where($data)->first();
            } else {
                $create = new Wowdb\Item\Create($data);
                $create->save();
            }
            if(!$this->creates()->where('create_id', $create->id)->exists())
                $this->creates()->attach($create->id);
        }
    }
    protected function parseSource(): void {
        $debug = [
            'category' => $this->getSourceCategory(),
            'drop' => $this->sourceIncludesDrop(),
            'quest' => $this->sourceIncludesQuest(),
            'vendor' => $this->sourceIncludesVendor()
        ];
        // grab quests
        if($this->sourceIncludesQuest()) {
            foreach($this->getSourceQuests() as $quest) {
                if(($questObj = Wowdb\Item\Quest::find($quest['questId'])) &&
                    !$this->quests()->where('quest_id', $quest['questId'])->exists())
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

        // grab drops
        if($type = $this->sourceIncludesDrop()) {
            if(($dropObj = Drop::where('category', $type)->first()) && !$this->drops->contains($dropObj->id))
                $this->drops()->attach($dropObj->id);
            else {
                $data = [
                    'category' => $type
                ];

                if($this->source instanceof ArrayObject) {
                    if($this->source->offsetExists('name'))
                        $data['npc'] = $this->source->offsetGet('name');

                    if($this->source->offsetExists('zone') && ($zoneRecord = Zone::where('name', $this->source->offsetGet('zone'))->first()))
                        $data['zone_id'] = $zoneRecord->id;

                    if($this->source->offsetExists('dropChance'))
                        $data['dropChance'] = floatval($this->source->offsetGet('dropChance'));
                    elseif($chance = $this->getTooltipDropchance())
                        $data['dropChance'] = $chance;

                    $this->drops()->create($data);
                }
            }
        }

        // grab vendor details
        if($this->sourceIncludesVendor()) {
            $vendorObj = Wowdb\Item\Buy::where('name', $this->getVendorName())->first();
            if($vendorObj && !$this->buys()->where('buy_id', $vendorObj->id)->exists())
                $this->buys()->attach($vendorObj->id);
            else {
                $data = [];
                if($name = $this->getVendorName())
                    $data['name'] = $name;
                if($faction = $this->getVendorFaction())
                    $data['faction'] = $faction;

                $this->vendorPrice = $this->getVendorCost();

                $this->buys()->create($data);
            }
        }
    }

    /*
     * @todo handle relationships by parsing tooltip, createdby and source
     * ---faction
     *   notes:
     *    tooltip array includes information like faction, class, bind-on-equip
     *    source contains list of quests/vendor(s) including faction
     *    [x] quests in source is an array
     *    createdBy array includes single amount array, reagents array, recipe array
     */

    public function getWowheadUrl(): string { return self::wowheadUrl($this->itemId); }
    public static function wowheadUrl(int $id): string {
        $domain = sprintf("&domain=%s", getenv('WOWHEAD_DOMAIN', 'classic'));
        //$domain = ((!empty(getenv('WOWHEAD_DOMAIN'))) ? "&domain=".getenv('WOWHEAD_DOMAIN') : '');
        $dsub = sprintf("%s/", getenv('WOWHEAD_DOMAIN', 'classic'));
        //$dsub = ((getenv('WOWHEAD_DOMAIN') === 'classic') ? "classic/" : '');

        return sprintf("https://www.wohead.com/%sitem%s%s", $dsub, $id, $domain);
    }

    public function getWowheadLinkHtml(): string { return self::wowheadLinkHtml($this->id, $this->name); }
    public static function wowheadLinkHtml(int $id, string $name): string {
        $iconsize = getenv('WOWHEAD_ICONSIZE', 'small');
        $url = self::getWowheadUrl($id);
        return sprintf(
            '<a data-wowhead="item=%d" target="_blank" data-wh-icon-size="%s" href="%s">%s</a>',
            $id, $iconsize, $url, $name
        );
    }

    /**
     * Define the one-to-many relationship with Drop.
     */
    public function drops(): HasMany { return $this->hasMany(Drop::class, 'item_id'); }

    /**
     * Define the many-to-many relationship with Race.
     */
    public function races(): BelongsToMany { return $this->belongsToMany(Race::class, 'item_races', 'item_id', 'race_id'); }
    public function hasRaceRestriction(): bool { return ($this->races->count() > 0); }

    /**
     * Define the many-to-many relationship with Klass.
     */
    public function klasses(): BelongsToMany { return $this->belongsToMany(Klass::class, 'item_klasses', 'item_id', 'klass_id'); }
    public function hasKlassRestriction(): bool { return ($this->klasses->count() > 0); }

    public function quests(): BelongsToMany { return $this->belongsToMany(Item\Quest::class, 'item_quests', 'item_id', 'quest_id'); }
    public function hasQuests(): int { return $this->quests->count(); }

    public function canAllianceUse(): bool { return ($this->faction === 'Alliance' || $this->faction === 'Both'); }
    public function canHordeUse(): bool { return ($this->faction === 'Horde' || $this->faction === 'Both'); }

    public static function convertPrice(int $price): string {
        $gold = floor($price / 10000);
        $silver = floor(($price % 10000) / 100);
        $copper = $price % 100;
        return sprintf("%dg %ds %dc", $gold, $silver, $copper);
    }

    public function creates(): BelongsToMany
    {
        return $this->belongsToMany(Item\Create::class, 'item_creates', 'item_id', 'create_id');
    }

    public function buys(): BelongsToMany
    {
        return $this->belongsToMany(Item\Buy::class, 'item_buys', 'item_id', 'buy_id');
    }
}
