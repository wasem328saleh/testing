<?php

namespace App\Models;

use App\Events\CreateEvent;
use App\Events\DeleteEvent;
use App\Events\UpdateEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'features';

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

//    public function featureable(): \Illuminate\Database\Eloquent\Relations\MorphTo
//    {
//        return $this->morphTo();
//    }

public function classification()
{
 return $this->belongsTo(Classification::class,'classification_id','id');
}

    public function advertisement_feature(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdvertisementFeature::class, 'feature_id', 'id');
    }

    public function property_sub_categories()
    {
        return $this->belongsToMany(
            PropertySubCategory::class,
            'features_property_sub_categories',
            'feature_id',
            'category_id');
    }
    public function translation()
    {
        return $this->morphOne(Translate::class, 'translatable');
    }
    protected static function booted()
    {
        parent::booted();
        static::created(function ($object) {
            event(new CreateEvent($object,'name'));
        });
        static::updated(function ($object) {
            event(new UpdateEvent($object,'name'));
        });
        static::deleted(function ($object) {
            event(new DeleteEvent($object,'name'));
        });
    }
}
