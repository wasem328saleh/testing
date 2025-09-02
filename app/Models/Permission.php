<?php

namespace App\Models;

use App\Events\CreateEvent;
use App\Events\DeleteEvent;
use App\Events\UpdateEvent;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
//    use SoftDeletes;

    public $table = 'permissions';

    protected array $dates = [
        'created_at',
        'updated_at',
//        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
//        'deleted_at',
    ];

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
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
