<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    //use HasFactory;

    /**
     * default name to use for the config values database table
     */
    const SHORTNAME = "need";
    const TABLENAME = self::SHORTNAME . 's';

    /**
     * for laravel to specify configurable values
     */
    const FILLABLE_COLUMNS = [
        'priority',
        'quantity',
        'toon_id',
        'item_id',
    ];

    /**
     * @var string $table
     */
    protected $table = self::TABLENAME;

    public static function getTableName(): string { return self::TABLENAME; }

    public static function tableBlueprint(\Illuminate\Database\Schema\Blueprint $table)
    {
        $table->id();

        $table->integer('priority')->default(1);
        $table->integer('quantity')->default(1);
        $table->string("toon_id");
        $table->integer("item_id")->nullable(false);
        $table->text('notes')->nullable(true);

        $table->foreign('toon_id')->references('id')->on(Toon::TABLENAME)
            ->onDelete('cascade');
        $table->foreign('item_id')->references('id')->on(Item::TABLENAME)
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
        foreach($data as $toon => $needs) {
            $toon = Toon::where('name', $toon)->first();
            foreach($needs as $id => $ndata) {
                $item = Item::where('wowhead_id', $id)->first();
                $vals = [
                    'toon_id' => $toon->id,
                    'item_id' => $item->id
                ];
                if(isset($ndata['priority'])) $vals['priority'] = $ndata['priority'];
                if(isset($ndata['quantity'])) $vals['quantity'] = $ndata['quantity'];
                if(isset($ndata['notes'])) $vals['notes'] = $ndata['notes'];
                self::create($vals);
            }
        }
    }

    public static function getNeedsByInstance(string $instance=null): ?array
    {
        $items = [];
        if(!empty($instance)) {
            $items[$instance] = [];
            $iitems = self::whereHas('item', function($query) use ($instance) {
                $query->where('instance', $instance);
            })->get();
            foreach($iitems as $need) {
                $toon = $need->toon;
                $item = $need->item;
                $wowhead_id = $item->wowhead_id;
                if(!array_key_exists($toon->name, $items[$instance]))
                    $items[$instance][$toon->name] = [];
                $items[$instance][$toon->name][$wowhead_id] = [
                    'priority' => $need->priority,
                    'quantity' => $need->quantity,
                    'notes' => $need->notes,
                    'item' => $item
                ];
            }
        } else {
            foreach(self::all() as $need) {
                $toon = $need->toon;
                $toonname = $toon->name;
                $item = $need->item;
                $instance = $item->instance;
                $wowhead_id = $item->wowhead_id;
                if(!array_key_exists($instance, $items))
                    $items[$instance] = [];
                if(!array_key_exists($toonname, $items[$instance]))
                    $items[$instance][$toonname] = [];
                $items[$instance][$toonname][$wowhead_id] = [
                    'priority' => $need->priority,
                    'quantity' => $need->quantity,
                    'notes' => $need->notes,
                    'item' => $item
                ];
            }
        }
        return $items;
    }

    public function toon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Toon::class);
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

}
