<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Toon extends Model
{
    //use HasFactory;
    // https://develop.battle.net/

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "toon";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'user_id',
        'name',
        'level',
        'gender',
        'role',
        'realm',
        'class_id'
    ];

    /**
     * @var string $table
     */
    protected $table = self::TABLENAME;

    public static function getTableName(): string { return self::TABLENAME; }

    public static function tableBlueprint(\Illuminate\Database\Schema\Blueprint $table): void
    {
        $table->id();

        $table->unsignedBigInteger('user_id');
        $table->string("name");
        $table->string("realm")->default('Mankrik');
        $table->string("guild")->nullable(true);
        $table->integer("level")->default(60);
        $table->enum('role', ['Tank', 'Healer', 'DPS'])->default('DPS');
        $table->integer("class_id")->nullable(false);
        $table->enum("gender", ['Male', 'Female'])->default('Female');

        $table->foreign('user_id')->references('id')->on(User::TABLENAME)
            ->onDelete('cascade');
        $table->foreign('class_id')->references('id')->on(Classification::TABLENAME)
            ->onDelete('cascade');
        $table->timestamps();
    }

    /**
     * list of user/class modifiable db table columns
     *
     * @var string[] $fillable
     */
    protected $fillable = self::FILLABLE_COLUMNS;

    public static function dbSeed(array $data): void
    {
        foreach($data as $name => $toon) {
            $newToon = new Toon();
            $newToon->name = $toon['name'];
            $newToon->level = $toon['level'];
            $newToon->gender = $toon['gender'];
            $newToon->realm = $toon['realm'];
            $newToon->guild = $toon['guild'];
            $newToon->role = $toon['role'];

            $cObj = Classification::where('race', $toon['classification']['race'])
                ->where('class', $toon['classification']['class'])
                ->where('spec', $toon['classification']['spec'])
                ->first();
            $newToon->class_id = $cObj->id;
            $newToon->save();
        }
    }

    public static function searchForToon($nameOrId, $realm=null): ?Toon
    {
        if(is_numeric($nameOrId))
            return Toon::find($nameOrId);
        elseif(!empty($nameOrId)) {
            $toon = Toon::where('name', $nameOrId);
            if(!empty($realm)) $toon->where('realm', $realm);
            return $toon->first();
        }
        return null;
    }

    public static function getAllToons(): array
    {
        $toonsel = Toon::select(['id', 'name']);
        if(!Auth::user()->can('view_all_toons'))
            $toonsel->where('user_id', Auth::id());
        $toons = $toonsel->get()->sortBy('name');

        $toonList = [];
        foreach($toons as $toon) {
            $toonList[$toon->id] = $toon->name;
        }
        return $toonList;
    }

    public static function ToonsWithNeeds(): array
    {
        $toonsel = Toon::select(['id', 'name']);
        //if(!Auth::user()->can('view_any_toon'))
        //    $toonsel->where('user_id', Auth::id());
        $toons = $toonsel->get()->sortBy('name');
        $toonList = [];
        foreach($toons as $toon) {
            if($toon->needs->count() > 0)
                $toonList[$toon->name] = $toon;
        }
        return $toonList;
    }

    public function getNeedItems(): array
    {
        $needItems = [];
        foreach($this->needs as $need) {
            $item = $need->item;
            $wowhead_id = $item->wowhead_id;
            $instance = $item->instance;
            if(!array_key_exists($instance, $needItems)) $needItems[$instance] = [];
            $needItems[$instance][$wowhead_id] = $item;
        }
        return $needItems;
    }

    public function classification(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classification::class, 'class_id', 'id');
    }

    public function needs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Need::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
