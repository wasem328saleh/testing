<?php

namespace App\Repositories\Mobile\AdvertisingPackage;

use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\SubscribeResource;
use App\Models\Advertisement;
use App\Models\AdvertisingPackage;
use App\Models\Classification;
use App\Models\Property;
use App\Models\Subscribe;
use App\Models\User;
use App\Repositories\Mobile\Properties\PropertiesUserRepositoryInterface;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdvertisingPackageUserRepository implements AdvertisingPackageUserRepositoryInterface
{
    use GeneralTrait;
    public PropertiesUserRepositoryInterface $properties;

    /**
     * @param PropertiesUserRepositoryInterface $properties
     */
    public function __construct(PropertiesUserRepositoryInterface $properties)
    {
        $this->properties = $properties;
    }
    public function get_all_packages($request)
    {
        // TODO: Implement get_all_packages() method.
        if (request()->role_name) {
           $user_type=request()->role_name;
           $packages=AdvertisingPackage::whereIn('user_type',[Str::lower($user_type),'both'])->get();
           return $this->returnData('packagesss',PackageResource::collection($packages->load('translation')),'');
        } else {
            $packages=AdvertisingPackage::where('user_type','both')->get();
            return $this->returnData('packages',PackageResource::collection($packages->load('translation')),'');
        }
    }
    public function subscribe_in_package($request)
    {
        // TODO: Implement subscribe_in_package() method.

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $package_id=$request->package_id;
            $package=AdvertisingPackage::where('id',$package_id)->first();
            $user_type=$package->user_type;
            if (Str::lower($user_type)!=Str::lower($user->roles()->first()->title)) {
                if (Str::lower($user_type)!='both')
                {
                return $this->returnError(403,'Not allowed');
                }
            }
            $subscription_start_date=Carbon::now()->toDate();
            $subscription_end_date=Carbon::now()->addDays($package->validity_period)->toDate();
            $user->subscribes()->create([
                'subscription_start_date'=>$subscription_start_date,
                'subscription_end_date'=>$subscription_end_date,
                'advertisements_count'=>$package->number_of_advertisements,
                'advertising_package_id'=>$package_id
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Done');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function add_advertising($request)
    {
        // TODO: Implement add_advertising() method.

        try {
            $user=Auth::user();
        if (Str::lower($user->roles()->first()->title)===Str::lower('Merchant')){
            if ($user->merchant_register_order->status==='pending'){
                return $this->returnError(403403,'Your membership request has not been processed yet.');
            }
            if ($user->merchant_register_order->status==='unacceptable'){
                return $this->returnError(403403,'Your membership application has not been accepted. Please check with the administration or your notifications to find out why.');
            }
        }
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->add_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_my_advertising($request)
    {
        // TODO: Implement get_my_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->get_my_properties();
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function edited_my_advertising($request)
    {
        // TODO: Implement edited_my_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->edited_my_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function deleted_my_advertising($request)
    {
        // TODO: Implement deleted_my_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->deleted_my_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function edited_activation_advertising($request)
    {
        // TODO: Implement edited_activation_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->edited_activation_my_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_advertising($request)
    {
        // TODO: Implement get_all_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    if ($request->input('city_id')){
                        return $this->properties->get_all_properties($request->input('city_id'));
                    }
                    return $this->properties->get_all_properties();
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_advertising_sale($request)
    {
        // TODO: Implement get_all_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    if ($request->input('city_id')){
                        return $this->properties->get_all_properties_sale($request->input('city_id'));

                    }
                    return $this->properties->get_all_properties_sale();
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_advertising_rent($request)
    {
        // TODO: Implement get_all_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    if ($request->input('city_id')){
                        return $this->properties->get_all_properties_rent($request->input('city_id'));
                    }
                    return $this->properties->get_all_properties_rent();
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function search_filter_advertising($request)
    {
        // TODO: Implement search_filter_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->search_filter_properties($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function search_advertising_by_cityId($request)
    {
        // TODO: Implement search_advertising_by_cityId() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->search_properties_by_cityId($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_my_subscriber($request)
    {
        // TODO: Implement get_my_subscriber() method.
        try {
            $user=Auth::user();
            $subscribers=$user->subscribes;
            return $this->returnData('my_subscribers', SubscribeResource::collection($subscribers->load('advertising_package.translation')));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function resubscribe_advertising($request)
    {
        // TODO: Implement resubscribe_advertising() method.
        try {
            $user=Auth::user();
            $subscribe_id=$request->subscribe_id;
            $exists=$this->relationship_exists($user->id,$subscribe_id,'subscribes',User::class);
            if ($exists) {
                $subscribe=Subscribe::findOrFail($subscribe_id);
                if (!$subscribe->active)
                {
                    $package=$subscribe->advertising_package;
                    if ($package)
                    {
                        $subscription_start_date=Carbon::now()->toDate();
                        $subscription_end_date=Carbon::now()->addDays($package->validity_period)->toDate();
                        $subscribe->subscription_start_date=$subscription_start_date;
                        $subscribe->subscription_end_date=$subscription_end_date;
                        $subscribe->used_advertisements_count=0;
                        $subscribe->active=true;
                        $subscribe->save();
                        return $this->returnSuccessMessage('Resubscribed successfully');
                    }
                    return $this->returnError('404', 'Package not found');
                }

                return $this->returnError('500', 'Subscribe Already Active');
            }
            return $this->returnError('404', 'Subscribe not found');
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function rate_advertising($request)
    {
        // TODO: Implement rate_advertising() method.
        try {
            DB::beginTransaction();
            $user=Auth::user();
            $advertisement_id=$request->advertisement_id;
            $score=$request->score;
            $advertisement=Advertisement::findOrFail($advertisement_id);
            $subscribe_id=$advertisement->subscribe->id;
            $exists=$this->relationship_exists($user->id,$subscribe_id,'subscribes',User::class);
            if (!$exists) {
                $advertisementable=$advertisement->advertisementable;
                if ($advertisementable->status==='active'&&$advertisementable->order->status==='posted'
                    &&$advertisement->active)
                {
                    $user->from_me_ratings()->create([
                        'rating_score'=>$score,
                        'ratingable_type'=>$advertisement->advertisementable_type,
                        'ratingable_id'=>$advertisement->advertisementable_id,
                    ]);
                    DB::commit();
                    return $this->returnSuccessMessage('Rated successfully');
                }
            }
            DB::rollBack();
            return $this->returnError('500', 'Working Error');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function add_medias($request)
    {
        // TODO: Implement add_media() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->add_property_photos($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_media($request)
    {
        // TODO: Implement delete_media() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->delete_property_photos($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function search_advertising_by_serialNumber($request)
    {
        // TODO: Implement search_advertising_by_serialNumber() method.
        try {
            $serial_number=$request->serial_number;
            $all=AdvertisementResource::collection(Advertisement::with(['advertisementable','subscribe.user'])
                ->where('active',true)
                ->whereHasMorph(
                    'advertisementable',
                    ['App\Models\Property'], // تحدید المودیلات المسموحة
                    function ($query) use ($serial_number) {
                        $query->where('serial_number','LIKE',"%{$serial_number}%");
                    }
                )
                ->get());
            return $this->returnData('advertising',$all);
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
