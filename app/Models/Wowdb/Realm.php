<?php

namespace App\Models\Wowdb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Realm extends Model
{
    //use HasFactory;

    const UNIVERSES = [
        "Classic ERA",
        /*
        "Classic SoD",
        "Classic HC",
        "Classic SoM",
        "Classic The Burning Crusade",
        "Classic Wrath of the Lich King",
        "Classic Cataclysm",
        "Classic Mists of Pandaria",
        "Retail"
        */
    ];
    const REALM_GROUPS = [
        "PVE East" => [
            "Ashkandi",
            "Mankrik",
            "Pagle",
            "Westfall",
            "Windseeker"
        ],
        "PVE West" => [
            "Atiesh",
            "Azuresong",
            "Myzrael",
            "Old Blanchy"
        ],
        "PVP East1" => [
            "Benediction",
            "Faerlina",
            "Heartseeker",
            "Incendius",
            "Netherwind"
        ],
        "PVP East2" => [
            "Earthfury",
            "Herod",
            "Kirtonos",
            "Kromcrush",
            "Skeram",
            "Stalagg",
            "Sulfuras",
            "Thalnos"
        ],
        "PVP West" => [
            "Anathema",
            "Arcanite Reaper",
            "Bigglesworth",
            "Blaumeux",
            "Fairbanks",
            "Kurinnaxx",
            "Rattlegore",
            "Smolderweb",
            "Thunderfury",
            "Whitemane"
        ],
        "OCE PVP" => [
            "Arugal",
            "Felstriker",
            "Yojamba"
        ]
    ];

    protected $connection = 'wowdb';

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "realm";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'id',
        'name',
        'battlegroup',
        'universe',
        'created_at',
        'updated_at'
    ];

    /**
     * @var string $table
     */
    protected $table = self::TABLENAME;

    public static function getTableName() { return self::TABLENAME; }

    public static function tableBlueprint(Blueprint $table): void
    {
        $table->id();
        $table->string('name', 32);
        $table->enum('battlegroup', array_keys(self::REALM_GROUPS));
        $table->enum('universe', self::UNIVERSES)->default(self::UNIVERSES[0]);
        $table->timestamps();
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

}
