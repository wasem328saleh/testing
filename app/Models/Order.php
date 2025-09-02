<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

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

    public function orderable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function classification()
    {
        return $this->belongsTo(Classification::class,'classification_id','id');
    }

    public function scopeValidPropertyAdvertisements($query)
    {
        return $query->where('orderable_type', Property::class)
            ->whereHas('orderable', function ($q) {
                $q->where('status', 'active')
                    ->whereHas('advertisement', function ($q) {
                        $q->where('active', true);
                    });
            });
    }
}
