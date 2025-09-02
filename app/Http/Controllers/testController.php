<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotification;
use App\Jobs\TraslateJob;
use App\Models\Feature;
use App\Models\User;
use App\Notifications\NotificationFCM;
use App\Traits\ApiResponderTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class testController extends Controller
{
    use ApiResponderTrait;

    public function testT()
    {

        $string='My Home';
        $localization=new GoogleTranslate();
        $localization->translate($string);
        $lang=$localization->getLastDetectedSource();
        $tr=new GoogleTranslate();
        $tr->setSource($lang);
        $tr->setTarget($lang);
        $value=$tr->translate($string);
        str_replace(' ','_',Str::lower($value));
        return str_replace(' ','_',Str::lower($value));
        $translations=[];
        return count($translations);
        /*name
         * category_services
         * countries
         * cities
         * regions
         * room_types
         * features
         * property_main_categories
         * property_sub_categories
         * ownership_types
         * pledge_types
         * classifications
         */

        /*title
         * advertising_packages
         * notifications
         * roles
         * permissions
         * directions
        */

        /*key
        * system_contact_information
        * detailed_attributes
        */

        $old_key='اراضي';
        $new_key='تجاري';
        $fileName='name';
        dispatch(new TraslateJob($new_key, $fileName,"update",$old_key));
        return "Done";
    }
    public function send_from_mobile()
    {
        $users=User::whereHas('roles',function($q){
            $q->where(function ($query){
                $query->where('title','super_admin')
                    ->orWhere('title','admin');
            });
        })->get();
        return $users;
        $title="title";
        $body="body";
        $type='success';
        $noti=new NotificationFCM();
        $user=User::where('id',1)->first();
        $notify=$user->notifications()->create([
            'title'=>$title,
            'body'=>$body,
            'type'=>$type,
        ]);
        $noti->send_notify($user->my_devices_token()->pluck('token')->toArray(),$notify);
    }

    public function send_notification_from_adamin()
    {
        //send notifications
        $title = "title";
        $body ="body";
        $type = 'info';

        $dd=User::where()->chunk(1,function ($users)use (&$title,&$body,&$type){
            $data=[
                'users'=>$users,
                'title'=>$title,
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));
        });
    }

    public function test_background(){
        //send notifications
        $title ="title";
        $body ="body";
        $type = 'info';

        $aaa=[];
        $dd=User::where('type','employee')->chunk(1,function ($users)use (&$title,&$body,&$type){
            $data=[
                'users'=>$users,
                'title'=>$title."  ".rand(1,100),
                'body'=>$body,
                'type'=>$type
            ];
            dispatch(new SendNotification($data));
        });
        return "Done send in background";
    }
    public function test(){
//        $rules=$this->get_all_rules();
//        $classificationId =1;
//        $sub_category_id=null;
//        $existingAttributes  = $this->config_attributes(intval($classificationId),$sub_category_id);
//        $attributes_name=[];
//        foreach ($existingAttributes as $attribute){
//            $attributes_name[]=$attribute['attribute_name'];
//        }
//        $filteredRules = array_intersect_key($rules, array_flip($attributes_name));
//        return $filteredRules;
//        $category_id=1;
//        $features=Feature::where('classification_id',1)
//            ->whereHas('property_sub_categories',function($q)use ($category_id){
//                $q->where('category_id',$category_id);
//            })
//            ->get();
//        return $features;
//        return Feature::all()->pluck('name');
//        $users=User::with('device_tokens')
//        ->whereHas('roles',function($q){
//            $q->where(function ($query){
//                $query->where('title','super_admin')
//                    ->orWhere('title','admin');
//            });
//        })->get();
//        return $users;
        $title="Waseem";
        $body="notifications using Ant Design's List component.
Unread Count: The number of unread notifications is calculated and displayed both in the dropdown and next to the notification icon in the button.
Dropdown Integration: The NotificationMenu is used as the overlay for the Dropdown, allowing it to show the notifications when clicked.";
        $type=['success','error','warning','info'];

        $noti=new NotificationFCM();
//        $user=User::where('id',1)->first();
//        $notify=$user->notifications()->create([
//            'title'=>$title,
//            'body'=>$body,
//            'type'=>$type[rand(0,count($type)-1)],
//        ]);
        $nn=[
            'id'=>222,
            'title'=>$title,
            'body'=>$body,
            'type'=>$type[rand(0,count($type)-1)],
            ];
        $user=User::findOrFail(1);
        $user2 =User::findOrFail(1)->device_tokens()->pluck('token')->toArray()[1];
        $notify = $user->notifications()->create([
            'title' => $title,
            'body' => $body,
            'type' => $type[rand(0,count($type)-1)],
        ]);
        return $noti->send_notify([$user2],$notify);
        return $noti->send_notify(["esYfJ-KPSU6b4reKTq1i3J:APA91bFqdFXycmdYTXFE-Z58dCBmxVPAUUo87JEmu47wayu_vrT2Ul6pKm_6ftBV7L3O608aTOpeIdETdHGnyFwzd0ihlDGOfTnVcUFiVPVzWGgH4Z-_8Yc"],$nn);
        return $noti->send_notify(
            "token",
            $notify);
//        return NotificationResource::collection($user->notifications()->get());
    }
}
