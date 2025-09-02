<?php

namespace App\Models;

use App\Events\CreateEvent;
use App\Events\DeleteEvent;
use App\Events\UpdateEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direction extends Model
{
    use HasFactory;

    protected $table = 'directions';

    protected $guarded = [];

    protected array $dates = [
        'created_at',
        'updated_at',
//        'deleted_at'
    ];

    /**
     * Get the value of an attribute using its mutator or by directly accessing it.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // Check if an accessor method exists for the attribute
        $accessor = 'get' . ucfirst($key) . 'Attribute';

        if (method_exists($this, $accessor)) {
            return $this->{$accessor}();
        }

        // If no accessor method exists, get the attribute directly
        return parent::getAttribute($key);
    }

    public function properties()
    {
        return $this->belongsToMany(Direction::class,'direction_property','direction_id','property_id');
    }
    public function translation()
    {
        return $this->morphOne(Translate::class, 'translatable');
    }
    protected static function booted()
    {
        parent::booted();
        static::created(function ($object) {
            event(new CreateEvent($object,'title'));
        });
        static::updated(function ($object) {
            event(new UpdateEvent($object,'title'));
        });
        static::deleted(function ($object) {
            event(new DeleteEvent($object,'title'));
        });
    }
}
