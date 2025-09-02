<?php

namespace App\Models;

use App\Events\CreateEvent;
use App\Events\DeleteEvent;
use App\Events\UpdateEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertySubCategory extends Model
{
    use HasFactory;

    protected $table = 'property_sub_categories';

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

    public function features()
    {
        return $this->belongsToMany(
            Feature::class,
            'features_property_sub_categories',
            'category_id',
            'feature_id');
    }

    public function config_attributes()
    {
        return $this->hasMany(ConfigAttribute::class, 'category_id','id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'category_id','id');
    }

    public function main_category()
    {
        return $this->belongsTo(PropertyMainCategory::class, 'main_category_id','id');
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
