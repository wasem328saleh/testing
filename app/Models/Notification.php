<?php

namespace App\Models;

use App\Events\CreateEvent;
use App\Events\DeleteEvent;
use App\Events\UpdateEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $guarded = [];

    protected array $dates = [
        'created_at',
        'updated_at'
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

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

//    protected static function booted()
//    {
//        parent::booted();
//        static::created(function ($object) {
//            event(new CreateEvent($object,'title'));
//        });
//        static::updated(function ($object) {
//            event(new UpdateEvent($object,'title'));
//        });
//        static::deleted(function ($object) {
//            event(new DeleteEvent($object,'title'));
//        });
//    }
}
