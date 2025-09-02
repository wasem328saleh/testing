<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $table = 'service_providers';

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

    public function categories()
    {
        return $this->belongsToMany(CategoryService::class,'category_service_service_provider','service_provider_id', 'category_service_id');
    }

    public function order()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id','id');
    }
}
