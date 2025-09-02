<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array<int, string>
    //  */
     protected $fillable = [
         'first_name',
         'last_name',
         'email',
         'password',
         'image_url',
         'secondary_address',
         'phone_number',
         'code',
         'verify',
         'region_id',
         'created_at',
         'updated_at',
         'deleted_at',
         'remember_token',
         'email_verified_at',
     ];
protected $guarded=[];
    protected array $dates = [
        'updated_at',
        'created_at',
//        'deleted_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setPasswordAttribute($input): void
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function getPermissionsAttribute()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->collapse()->merge($this->permissions()->get());
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratingable');
    }

    public function subscribes()
    {
        return $this->hasMany(Subscribe::class,'user_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'user_id','id');
    }

    public function orders_ad()
    {
        return $this->orders()->where('for_service_provider',0);
    }

    public function services()
    {
        return $this->orders()
            ->where('for_service_provider',true)
            ->where('status','posted')
            ->whereHas('orderable',function ($query){
                $query->where('status','accept');
            })
            ->with(['orderable.categories','orderable.ratings'])->get()->pluck('orderable');
    }
    public function advertising()
    {
        return $this->orders_ad()

            ->where('status','posted')
            ->where('orderable_type','App\\Models\\Property')
            ->whereHas('orderable',function ($query){

                $query->where('status','active')
                    ->whereHas('advertisement',function ($query){
                        $query->where('active',true);
                    });
            })
            ->with('orderable.advertisement.advertisementable')
            ->get()->pluck('orderable');
    }
    public function interests()
    {
        return $this->hasMany(Interests::class,'user_id','id');
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class,'user_id','id');
    }


    public function from_me_ratings()
    {
        return $this->hasMany(Rating::class,'user_id','id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class,'user_id','id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class,'user_id','id');
    }

    public function personal_identification_papers()
    {
        return $this->hasMany(PersonalIdentificationDocumentPaper::class,'user_id','id');
    }

    public function device_tokens()
    {
        return $this->hasMany(DeviceToken::class,'user_id','id');
    }

    public function search_records()
    {
        return $this->hasMany(SearchRecord::class,'user_id','id');
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class,'user_id','id');
    }

    public function security_settings()
    {
        return $this->hasOne(SecuritySetting::class,'user_id','id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id','id');
    }

    public function merchant_register_order()
    {
        return $this->hasOne(MerchantRegisterOrder::class,'user_id');
    }
}
