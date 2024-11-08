<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    //use HasFactory;

    const RACEDATA = [
        'Alliance' => [
            'Human' => [
                'Warrior',
                'Paladin',
                'Rogue',
                'Priest',
                'Mage',
                'Warlock'
            ],
            'Dwarf' => [
                'Warrior',
                'Paladin',
                'Rogue',
                'Priest',
                'Hunter'
            ],
            'Night Elf' => [
                'Warrior',
                'Rogue',
                'Priest',
                'Hunter',
                'Druid'
            ],
            'Gnome' => [
                'Warrior',
                'Rogue',
                'Mage',
                'Warlock'
            ]
        ],
        'Horde' => [
            'Orc' => [
                'Warrior',
                'Rogue',
                'Warlock',
                'Hunter',
                'Shaman'
            ],
            'Undead' => [
                'Warrior',
                'Rogue',
                'Priest',
                'Mage',
                'Warlock'
            ],
            'Tauren' => [
                'Warrior',
                'Hunter',
                'Druid',
                'Shaman'
            ],
            'Troll' => [
                'Warrior',
                'Rogue',
                'Priest',
                'Mage',
                'Hunter',
                'Shaman'
            ]
        ]
    ];

    public static function getRaceFaction(string $race): string
    {
        foreach(self::RACEDATA as $faction => $races) {
            if(array_key_exists($race, $races))
                return $faction;
        }
        throw new \Exception("Faction not found for race $race");
    }
    public static function getRacesForClass(string $class): array
    {
        $races = [];
        foreach(self::RACEDATA as $faction => $racesub) {
            foreach($racesub as $race => $classes) {
                if(in_array($class, $classes))
                    $races[] = $race;
            }
        }
        return $races;
    }

    public static function getClassList(string $race=null): array
    {
        $classlist = [];
        if(array_key_exists($race, self::RACEDATA['Alliance'])) {
            $classlist = self::RACEDATA['Alliance'][$race];
        } elseif(array_key_exists($race, self::RACEDATA['Horde'])) {
            $classlist = self::RACEDATA['Horde'][$race];
        } else {
            foreach(self::RACEDATA as $faction => $races) {
                foreach($races as $racesub) {
                    $classlist = array_merge($classlist, $racesub);
                }
            }
        }

        return $classlist;
    }

    public static function getRaceList(string $filter=null): array
    {
        $racelist = [];
        if(array_key_exists($filter, self::RACEDATA)) {
            $racelist = array_keys(self::RACEDATA[$filter]);
        } else {
            foreach(self::RACEDATA as $faction => $races) {
                $racesub = array_keys($races);
                $racelist = array_merge($racelist, $racesub);
            }
        }
        return $racelist;
    }

    const SPECDATA = [
        'Mage' => [
            'Arcane',
            'Fire',
            'Frost'
        ],
        'Warrior' => [
            'Arms',
            'Fury',
            'Protection'
        ],
        'Rogue' => [
            'Assassination',
            'Combat',
            'Subtlety'
        ],
        'Priest' => [
            'Discipline',
            'Holy',
            'Shadow'
        ],
        'Hunter' => [
            'Beast Mastery',
            'Marksmanship',
            'Survival'
        ],
        'Warlock' => [
            'Affliction',
            'Demonology',
            'Destruction'
        ],
        'Paladin' => [
            'Holy',
            'Protection',
            'Retribution'
        ],
        'Shaman' => [
            'Elemental',
            'Enhancement',
            'Restoration'
        ],
        'Druid' => [
            'Balance',
            'Feral',
            'Restoration'
        ]
    ];

    public static function getAllSpecs(): array
    {
        $specs = [];
        foreach(static::all() as $cf) {
            $cfid = intval($cf->id);
            $race = $cf->race;
            if(!array_key_exists($race, $specs)) $specs[$race] = [];
            $specs[$race][$cfid] = sprintf("%s: %s", $cf->class, $cf->spec);
        }
        return $specs;
    }

    public static function getUniqueSpecList(string $faction=null): array
    {
        $specs = [];
        if(array_key_exists($faction, self::SPECDATA)) {
            $specs = self::SPECDATA[$faction];
        } else {
            foreach(self::SPECDATA as $class => $spec)
                $specs = $spec;
        }
        return $specs;
    }

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "classification";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'faction',
        'race',
        'class',
        'spec'
    ];

    /**
     * @var string $table
     */
    protected $table = self::TABLENAME;

    public static function getTableName(): string { return self::TABLENAME; }

    public static function tableBlueprint(\Illuminate\Database\Schema\Blueprint $table)
    {
        $table->id();
        $table->enum('faction', ['Alliance', 'Horde']);
        $table->enum('race', self::getRaceList());
        $table->enum("class", array_keys(self::SPECDATA));
        $table->enum("spec", self::getUniqueSpecList());

        $table->index(['faction','race','class','spec']);
        $table->timestamps();
    }

    public static function dbSeed(): void
    {
        foreach(self::RACEDATA as $faction => $races) {
            foreach ($races as $race => $classes) {
                foreach ($classes as $class) {
                    foreach (self::SPECDATA[$class] as $spec) {
                        $newClassification = new Classification();
                        $newClassification->faction = $faction;
                        $newClassification->race = $race;
                        $newClassification->class = $class;
                        $newClassification->spec = $spec;
                        $newClassification->save();
                    }
                }
            }
        }
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public function toon()
    {
        return $this->belongsTo(Toon::class);
    }
}
