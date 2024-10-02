<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //use HasFactory;
    // https://www.wowhead.com/tooltips
    // https://www.wowhead.com/item=#####&xml

    const DROPTYPES = [
        'any',
        'loot',
        'boss',
        'mob',
        'npc',
        'rare',
        'pickpocket',
        'container',
        'quest',
        'event',
        'reward',
        'blizzard',
        'vendor',
        'pvp',
        'craft',
        'world-drop',
        'zone-drop',
        'other'
    ];

    const FEATURES = [
        "priest",
        "mage",
        "warlock",
        "druid",
        "hunter",
        "shaman",
        "paladin",
        "warrior",
        "rogue",
        "staff",
        "polearm",
        "dagger",
        "sword",
        "mace",
        "axe",
        "fist",
        "shield",
        "bow",
        "crossbow",
        "gun",
        "wand",
        "thrown",
        "off-hand",
        "main-hand",
        "cloth",
        "leather",
        "mail",
        "plate",
        "tailoring",
        "leatherworking",
        "blacksmithing",
        "alchemy",
        "herbalism",
        "mining",
        "skinning",
        "enchanting",
        "engineering",
        "cooking",
        "first-aid",
        "fishing",
        "unique",
        "not-unique"
    ];
    const SLOTS = [
        'head',
        'neck',
        'shoulder',
        'back',
        'chest',
        'wrist',
        'hands',
        'waist',
        'legs',
        'feet',
        'finger',
        'trinket',
        'one-hand weapon',
        'two-hand weapon',
        'off-hand',
        'ranged weapon',
        'quiver-ammo',
        'relic-idol-totem',
        'buff',
        'quest-item',
        'recipe',
        'consumable',
        'container',
        'key',
        'misc',
    ];

    const INSTANCES = [
        'Azeroth' => [
            'PvE' => "Outside"
        ],
        'Rep-Honor' => [
            'Argent Dawn' => 'Eastern Plaguelands',
            'Thorium Brotherhood' => 'Searing Gorge',
            'Timbermaw Hold' => 'Felwood',
            'Cenarion Circle' => 'Silithus',
            'Hydraxian Waterlords' => 'Azshara',
            'Zandalar Tribe' => 'Stranglethorn Vale',
            'Warsong Gulch' => 'Ashenvale',
            'Arathi Basin' => 'Arathi Highlands',
            'Alterac Valley' => 'Hillsbrad Foothills',
            'Stormwind City' => 'Elwynn Forest',
            'Orgrimmar' => 'Durotar',
        ],
        'Eastern Kingdoms' => [
            'The Deadmines' => 'Westfall',
            'Shadowfang Keep' => 'Silverpine Forest',
            'The Stockade' => 'Stormwind City',
            'Gnomeregan' => 'Dun Morogh',
            'Scarlet Monastery: Graveyard' => 'Tirisfal Glades',
            'Scarlet Monastery: Library' => 'Tirisfal Glades',
            'Scarlet Monastery: Armory' => 'Tirisfal Glades',
            'Scarlet Monastery: Cathedral' => 'Tirisfal Glades',
            'Uldaman' => 'Badlands',
            "Sunken Temple of Atal=Hakkar" => 'Swamp of Sorrows',
            'Blackrock Depths' => 'Burning Steppes',
            'Scholomance' => 'Western Plaguelands',
            'Stratholme: Live Side' => 'Eastern Plaguelands',
            'Stratholme: Undead Side' => 'Eastern Plaguelands',
            'Blackrock Spire: Lower' => 'Burning Steppes',
            'Blackrock Spire: Upper' => 'Burning Steppes',
            'Blackwing Lair' => 'Burning Steppes',
            'Molten Core' => 'Burning Steppes',
            'Zul-Gurub' => 'Stranglethorn Vale',
            'Naxxramas' => 'Eastern Plaguelands',
            'Lord Kazzak' => 'Blasted Lands',
            'Emeriss' => 'Duskwood',
            'Lethon' => 'The Hinterlands',
        ],
        'Kalimdor' => [
            'Ragefire Chasm' => 'Orgrimmar',
            'Wailing Caverns' => 'The Barrens',
            'Blackfathom Deeps' => 'Ashenvale',
            'Razorfen Kraul' => 'The Barrens',
            'Razorfen Downs' => 'The Barrens',
            'Zul-Farrak' => 'Tanaris',
            'Maraudon' => 'Desolace',
            'Dire Maul: Arena' => 'Feralas',
            'Dire Maul: East' => 'Feralas',
            'Dire Maul: West' => 'Feralas',
            'Dire Maul: North' => 'Feralas',
            'Onyxias Lair' => 'Dustwallow Marsh',
            'Ruins of Ahn-Qiraj' => 'Silithus',
            'Temple of Ahn-iraj' => 'Silithus',
            'Azuregos' => 'Azshara',
            'Ysondre' => 'Feralas',
            'Taerar' => 'Ashenvale',
        ],
    ];

    public static function dupeKeys(array $indexed): array
    {
        $output = [];
        foreach($indexed as $value)
            $output[$value] = $value;
        return $output;
    }

    public static function getContinents(): array {
        $instances = array_keys(self::INSTANCES);
        sort($instances);
        return $instances;
    }
    public static function getInstanceList(string $filter=null, string $zone=null): array
    {
        $instances = [];
        if(array_key_exists($filter, self::INSTANCES)) {
            if(!empty($zone)) {
                foreach(self::INSTANCES[$filter] as $instance => $zone_name)
                    if($zone_name == $zone)
                        $instances[] = $instance;
            } else
                $instances[$filter] = array_keys(self::INSTANCES[$filter]);
        } else {
            foreach(self::INSTANCES as $continent => $cinstances) {
                if(!empty($zone)) {
                    foreach($cinstances as $instance => $zone_name)
                        if($zone_name == $zone)
                            $instances[] = $instance;
                } else
                    $instances = array_merge($instances, array_keys($cinstances));
            }
        }
        sort($instances);
        return $instances;
    }

    const ZONES = [
        'Azeroth' => [
            'Outside', 'Instances', 'Raids', 'World-Bosses', 'All'
        ],
        'Battlegrounds' => [
            'Warsong Gulch',
            'Arathi Basin',
            'Alterac Valley'
        ],
        'Eastern Kingdoms' => [
            'Stormwind City',
            'Ironforge',
            'Undercity',
            'Thunder Bluff',
            'Alterac Mountains',
            'Arathi Highlands',
            'Badlands',
            'Blasted Lands',
            'Burning Steppes',
            'Deadwind Pass',
            'Dun Morogh',
            'Duskwood',
            'Eastern Plaguelands',
            'Elwynn Forest',
            'Hillsbrad Foothills',
            'Loch Modan',
            'Redridge Mountains',
            'Searing Gorge',
            'Silverpine Forest',
            'Stranglethorn Vale',
            'Swamp of Sorrows',
            'The Hinterlands',
            'Tirisfal Glades',
            'Western Plaguelands',
            'Westfall',
            'Wetlands',
        ],
        'Kalimdor' => [
            'Darnassus',
            'Orgrimmar',
            'Ashenvale',
            'Azshara',
            'Darkshore',
            'Darnassus',
            'Desolace',
            'Durotar',
            'Dustwallow Marsh',
            'Felwood',
            'Feralas',
            'Moonglade',
            'Mulgore',
            'Orgrimmar',
            'Silithus',
            'Stonetalon Mountains',
            'Tanaris',
            'Teldrassil',
            'The Barrens',
            'Thousand Needles',
            'Thunder Bluff',
            'Un-Goro Crater',
            'Winterspring',
        ],
    ];

    public static function getZoneList(string $filter=null): array
    {
        $zones = [];
        if(array_key_exists($filter, self::ZONES)) {
            $zones[$filter] = self::ZONES[$filter];
            return $zones;
        } else {
            foreach(self::ZONES as $continent => $czones)
                $zones = array_merge($zones, $czones);
        }
        sort($zones);
        return $zones;
    }

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "item";

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'wowhead_id',
        'name',
        'continent',
        'zone',
        'instance',
        'boe',
        'random_enchant',
        'droptype',
        'feature',
        'drop_rate',
        'sources',
        'slot',
        'notes'
    ];

    /**
     * @var string $table
     */
    protected $table = self::SHORTNAME . 's';

    public static function getTableName() { return self::SHORTNAME . 's'; }

    public static function getTableBlueprint(\Illuminate\Database\Schema\Blueprint $table)
    {
        $table->id();

        $table->integer('wowhead_id')->nullable(true);
        $table->string("name");
        $table->enum('continent', array_keys(self::ZONES))->nullable(true);
        $table->enum('zone', self::getZoneList())->nullable(true);
        $table->enum('instance', self::getInstanceList())->nullable(true);
        $table->boolean('boe')->default(false);
        $table->boolean('random_enchant')->default(false);
        $table->enum('droptype', self::DROPTYPES)->default('boss')->nullable(true);
        $table->enum('feature', self::FEATURES)->nullable(true);
        $table->float('drop_rate')->nullable(true);
        $table->string('sources')->nullable(true);
        $table->enum('slot', self::SLOTS)->nullable(false);
        $table->text('notes')->nullable(true);

        $table->timestamps();
    }

    public static function dbSeed(array $data): void
    {
        foreach($data as $wid => $item) {
            $source_json = (is_array($item['sources']) && (count($item['sources']) > 0)) ? json_encode($item['sources']) : null;
            $item['sources'] = $source_json;
            foreach($item as $key => $value)
                if(empty($value))
                    unset($item[$key]);
            self::create($item);
        }
    }


    public static function getAllItems(): array
    {
        $items = self::all()->sortBy('name');
        $item_list = [];
        foreach($items as $item)
            $item_list[$item->id] = $item->name;
        return $item_list;
    }

    public static function getSlotItems(): array
    {
        $items = self::all();
        $item_list = [];
        foreach($items as $item)
            $item_list[$item->id] = $item->slot . ":" . $item->name;
        asort($item_list);
        return $item_list;
    }

    public static function getWowheadXml(int|string $id)
    {
        $url = sprintf("https://www.wowhead.com/item=%s&xml", intval($id));
        $xml = file_get_contents($url);
        $data = simplexml_load_string($xml);
        return $data;
    }

    public function setContinent(string $continent): void
    {
        if(array_key_exists($continent, self::ZONES))
            $this->Continent = $continent;
    }

    public function findZoneContinent(string $zone): string|null
    {
        foreach(self::ZONES as $continent => $zones)
            if(in_array($zone, $zones))
                return $continent;
        return null;
    }

    public function setZone(string $zone): void
    {
        if(!empty($this->getAttribute('Continent'))) {
            $zone_continent = $this->findZoneContinent($zone);
            if($zone_continent == $this->getAttribute('Continent'))
                $this->Zone = $zone;
            else
                throw new \Exception("Zone $zone is not in set Continent");

        } else {
            $continent = $this->findZoneContinent($zone);
            if($continent) {
                $this->Continent = $continent;
                $this->Zone = $zone;
            } else
                throw new \Exception("Zone $zone is not found in any continent");
        }
    }

    public function setInstance(string $instance): void
    {
        if(in_array($instance, self::INSTANCES))
            $this->Instance = $instance;
    }

    public function getSourceList(): array
    {
        $list = json_decode($this->sources, true);
        if(!is_array($list)) $list = [];
        return $list;
    }

    public function addToSourceList(string $source): void
    {
        $sources = $this->getSourceList();
        if(!is_array($sources)) $sources = [];
        if(!in_array($source, $sources))
            $sources[] = $source;
        $this->sources = json_encode($sources);
    }

    public function removeFromSourceList(string $source): void
    {
        $sources = $this->getSourceList();
        if(!is_array($sources)) $sources = [];
        if(in_array($source, $sources))
            unset($sources[array_search($source, $sources)]);
        if(count($sources) == 0)
            $this->sources = null;
        else
            $this->sources = json_encode($sources);
    }

    public static function getNeedInstanceList(): array
    {
        $instances = [];
        foreach(Need::all() as $need) {
            $instance = $need->item->instance;
            if(!in_array($instance, $instances))
                $instances[$instance] = $need->item;
        }
        ksort($instances);
        return $instances;
    }
    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function need(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Need::class);
    }
}
