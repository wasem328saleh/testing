<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $guarded = [];

    protected array $dates = [
        'created_at',
        'updated_at',
//        'deleted_at'
    ];
    protected $casts = [
        'price_history' => 'array',
        'rent_price' => 'array',
    ];

    public function getPriceAttribute()
    {
        return (int)$this->price_history['price'];
    }
    public function getHistoryAttribute()
    {
        return $this->price_history['history'];
    }
    public function getRentTypeAttribute()
    {
        return $this->rent_price['type'];
    }
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

    public function advertisement()
    {
        return $this->morphOne(Advertisement::class, 'advertisementable');
    }

    public function order()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function medias()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function detailed_attributes()
    {
        return $this->morphMany(DetailedAttribute::class, 'attributeable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    public function features()
    {
        return $this->morphMany(AdvertisementFeature::class, 'adable');
    }


    public function ownership_papers()
    {
        return $this->hasMany(PropertyOwnershipDocumentPaper::class, 'property_id','id');
    }

    public function rooms()
    {
        return $this->hasMany(PropertyRoom::class, 'property_id','id');
    }

    public function ownership_type()
    {
        return $this->belongsTo(OwnershipType::class, 'ownership_type_id','id');
    }

    public function pledge_type()
    {
        return $this->belongsTo(PledgeType::class, 'pledge_type_id','id');
    }

    public function sub_category()
    {
        return $this->belongsTo(PropertySubCategory::class, 'category_id','id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id','id');
    }

    public function directions()
    {
        return $this->belongsToMany(Direction::class,'direction_property', 'property_id', 'direction_id');
    }

}
