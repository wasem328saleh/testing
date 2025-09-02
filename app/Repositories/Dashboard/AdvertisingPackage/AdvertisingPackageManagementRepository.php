<?php

namespace App\Repositories\Dashboard\AdvertisingPackage;

use App\Http\Resources\AdminPackageResource;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use App\Http\Resources\ConfigAttributesResource;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\IdNameResource;
use App\Models\AdvertisingPackage;
use App\Models\Classification;
use App\Models\ConfigAttribute;
use App\Models\Feature;
use App\Models\Order;
use App\Models\Property;
use App\Models\Role;
use App\Repositories\Dashboard\Properties\PropertiesManagementRepositoryInterface;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdvertisingPackageManagementRepository implements AdvertisingPackageManagementRepositoryInterface
{

    use GeneralTrait;
    public PropertiesManagementRepositoryInterface $properties;

    /**
     * @param PropertiesManagementRepositoryInterface $properties
     */
    public function __construct(PropertiesManagementRepositoryInterface $properties)
    {
        $this->properties = $properties;
    }


    public function get_all_advertising_packages()
    {
        // TODO: Implement get_all_advertising_packages() method.
        try {
            DB::beginTransaction();
            $packages = AdvertisingPackage::all();
            DB::commit();
            return $this->returnData('packages', AdminPackageResource::collection($packages->load('translation')),'This Is All Packages');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function add_advertising_package($request)
    {
        // TODO: Implement add_advertising_package() method.
        try {
            DB::beginTransaction();
            $title=request()->title;
            $price=request()->price;
            $validity_period=request()->validity_period;
            $number_of_advertisements=request()->number_of_advertisements;
            $validity_period_per_advertisement=request()->validity_period_per_advertisement;
            $description=request()->description?request()->description:'';
            $user_type=request()->user_type?request()->user_type:'both';
            $price_history=[
                'price'=>intval($price),
                'history'=>[]
            ];
            AdvertisingPackage::create([
                'title'=>$title,
                'price_history'=>$price_history,
                'validity_period'=>$validity_period,
                'number_of_advertisements'=>$number_of_advertisements,
                'validity_period_per_advertisement'=>$validity_period_per_advertisement,
                'description'=>$description,
                'user_type'=>$user_type,
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Advertising Package Added Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function update_advertising_package($request)
    {
        // TODO: Implement update_advertising_package() method.
        try {
            DB::beginTransaction();
            $package_id=request()->package_id;
            $package=AdvertisingPackage::where('id',$package_id)->first();
            if (!$package){
                DB::rollBack();
                return $this->returnError('404','Package Not Found');
            }
            $package->title=request()->title?request()->title:$package->title;
            $package->validity_period=request()->validity_period?request()->validity_period:$package->validity_period;
            $package->number_of_advertisements=request()->number_of_advertisements?request()->number_of_advertisements:$package->number_of_advertisements;
            $package->validity_period_per_advertisement=request()->validity_period_per_advertisement?request()->validity_period_per_advertisement:$package->validity_period_per_advertisement;
            $package->description=request()->description?request()->description:$package->description;
            $package->user_type=request()->user_type?request()->user_type:$package->user_type;
            if (request()->price!=null){

                $price=request()->price;
                if (!($price==$package->price)){
//                    return $package->history;
                    $h=collect($package->history)->toArray();
                    array_push($h,intval($package->price));
                    $package->price_history=[
                        'price'=>intval($price),
                        'history'=>$h
                    ];
                }
            }
            $package->save();
            DB::commit();
            return $this->returnSuccessMessage('Package Updated Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
        }
    }

    public function delete_advertising_package($request)
    {
        // TODO: Implement delete_advertising_package() method.
        try {
            DB::beginTransaction();
            $package_id=request()->package_id;
            $package=AdvertisingPackage::where('id',$package_id)->first();
            if (!$package){
                DB::rollBack();
                return $this->returnError('404','Package Not Found');
            }
            $package->delete();
            DB::commit();
            return $this->returnSuccessMessage('Package Deleted Successfully');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->returnError($exception->getCode(),$exception->getMessage());
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
                    $properties=$this->properties->get_all_properties();
                    if ($properties){
                        return $this->returnData('advertising',$properties);
                    }
                    return $this->returnError('404', 'Working Error');
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function add_advertising($request)
    {
        // TODO: Implement add_advertising() method.
        try {
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

    public function update_advertising($request)
    {
        // TODO: Implement update_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->update_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_advertising($request)
    {
        // TODO: Implement delete_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->delete_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function change_status_advertising($request)
    {
        // TODO: Implement change_status_advertising() method.
        try {
            $classification_id=$request->classification_id;
            $classification_name=Classification::findOrFail($classification_id)->name;
            switch ($classification_name) {
                case 'properties':{
                    return $this->properties->edited_activation_property($request);
                }
                default:{
                    return $this->returnError('404', 'Class not found');
                }
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function get_all_archived_advertising()
    {
        // TODO: Implement get_all_archived_advertising() method.
    }

    public function remove_from_archived($request)
    {
        // TODO: Implement remove_from_archived() method.
    }

    public function unarchived_from_archived($request)
    {
        // TODO: Implement unarchived_from_archived() method.
    }

    public function get_all_features($request)
    {
        // TODO: Implement get_all_features() method.
        try {
            $features = Feature::where('classification_id', request()->classification_id)
                ->whereHas('property_sub_categories', function($q) {
                    $q->where('property_sub_categories.id', request()->category_id); // Specify table name
                })
                ->with('translation')->get();
            return $this->returnData('features',IdNameResource::collection($features));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function add_feature($request)
    {
        // TODO: Implement add_feature() method.
        try {
            DB::beginTransaction();
            $classification_id=$request->classification_id;
            $name=$request->name;
            Feature::create([
                'name'=>$name,
                'classification_id'=>$classification_id,
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Feature Added Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_feature($request)
    {
        // TODO: Implement update_feature() method.
        try {
            DB::beginTransaction();
            $feature_id=$request->feature_id;
            $feature=Feature::findOrFail($feature_id);
            if (!$feature){
                DB::rollBack();
                return $this->returnError('404', 'Feature Not Found');
            }
            $feature->name=request()->name?request()->name:$feature->name;
            $feature->classification_id=request()->classification_id?request()->classification_id:$feature->classification_id;
            $feature->save();
            DB::commit();
            return $this->returnSuccessMessage('Feature Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_feature($request)
    {
        // TODO: Implement delete_feature() method.
        try {
            DB::beginTransaction();
            $feature_id=$request->feature_id;
            $feature=Feature::findOrFail($feature_id);
            if (!$feature){
                DB::rollBack();
                return $this->returnError('404', 'Feature Not Found');
            }
            $feature->delete();
            DB::commit();
            return $this->returnSuccessMessage('Feature Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function get_all_classifications()
    {
        // TODO: Implement get_all_classifications() method.
        try {
            $classifications=Classification::where('active',true)->with('translation')->get();
            return $this->returnData('classifications',IdNameResource::collection($classifications));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function add_classification($request)
    {
        // TODO: Implement add_classification() method.
        try {
            DB::beginTransaction();
            $name=$request->name;
            Classification::create([
                'name'=>$name,
            ]);
            DB::commit();
            return $this->returnSuccessMessage('Classification Added Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function update_classification($request)
    {
        // TODO: Implement update_classification() method.
        try {
            DB::beginTransaction();
            $classification_id=$request->classification_id;
            $classification=Classification::findOrFail($classification_id);
            if (!$classification){
                DB::rollBack();
                return $this->returnError('404', 'Class not found');
            }
            if (request()->name!=null){
                $classification->name=request()->name;
                $classification->save();
            }
            DB::commit();
            return $this->returnSuccessMessage('Classification Updated Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function delete_classification($request)
    {
        // TODO: Implement delete_classification() method.
        try {
            DB::beginTransaction();
            $classification_id=$request->classification_id;
            $classification=Classification::findOrFail($classification_id);
            if (!$classification){
                DB::rollBack();
                return $this->returnError('404', 'Class not found');
            }
            $classification->delete();
            DB::commit();
            return $this->returnSuccessMessage('Classification Deleted Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function change_status_classification($request)
    {
        // TODO: Implement change_status_classification() method.
        try {
            DB::beginTransaction();
            $classification_id=$request->classification_id;
            $classification=Classification::findOrFail($classification_id);
            if (!$classification){
                DB::rollBack();
                return $this->returnError('404', 'Class not found');
            }
            if ($classification->active){
                $classification->active=false;
                $classification->save();
                DB::commit();
                return $this->returnSuccessMessage('Classification Changed Status Successfully');
            }
            $classification->active=true;
            $classification->save();
            DB::commit();
            return $this->returnSuccessMessage('Classification Changed Status Successfully');
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function getAdvertisementById($id)
    {
        // TODO: Implement getAdvertisementById() method.
        try {
            $advertisement=Advertisement::where('id',$id)
                ->with(['advertisementable','subscribe.user'])
                ->where('active',true)
                ->orderByDesc('created_at')
                ->first();
            if (!$advertisement){
                return $this->returnData('advertisement',[],'Advertisement Not Found');
            }
            return $this->returnData('advertisement',new AdvertisementResource($advertisement));
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
