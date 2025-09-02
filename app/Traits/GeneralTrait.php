<?php

namespace App\Traits;



use App\Http\Resources\ConfigAttributesResource;
use App\Http\Resources\TaskResource;
use App\Models\AdvertisingPackage;

use App\Models\Classification;
use App\Models\Complaint;
use App\Models\ConfigAttribute;

use App\Models\Property;
use App\Models\Order;
use App\Models\CategoryService;
use App\Models\Region;
use App\Models\ServiceProvider;
use App\Models\User;

use Carbon\Carbon;

use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Nette\Utils\Random;


trait GeneralTrait
{

    public function update_history_ads()
    {
        $all_oo=Order::where('for_service_provider',false)
            ->where('status','posted')
            ->whereHasMorph(
                'orderable',
                ['App\Models\Property'], // تحدید المودیلات المسموحة
                function ($query) {
                    $query->where('status', 'active')
                        ->whereHas('advertisement', function ($query) {
                            $query->where('active', true);
                        });
                }
            )
            ->with('orderable.advertisement.advertisementable')->get();
        foreach ($all_oo as $oo) {
            $aa=$oo->orderable->advertisement->advertisementable;
            $pp=$aa->price_history_price;
            $h_pp=$pp+($pp/2);
            $aa->update(
                [
                    'price_history'=>[
                        'price'=>$aa->price_history_price,
                        'history'=>$h_pp
                    ]
                ]
            );
        }
    }
    public function generate_session_services()
    {
        DB::table('category_services')->delete();
        $category_services=
            [
                [
                    //default
                    'name' => 'خدمات متنوعة',
                    'image_url'=>'fake_image_services/category_service/default.png'
                ],
                [
                    //a
                    'name' => 'أثاث',
                    'image_url'=>'fake_image_services/category_service/a.png'
                ],
                [
                    //b
                    'name' => 'أجهزة كهربائية',
                    'image_url'=>'fake_image_services/category_service/b.png'
                ],
                [
                    //c
                    'name' => 'خدمات الإنترنت',
                    'image_url'=>'fake_image_services/category_service/c.jpeg'
                ],
                [
                    //d
                    'name' => 'التنظيف',
                    'image_url'=>'fake_image_services/category_service/d.jpeg'
                ],
                [
                    //e
                    'name' => 'محاميين',
                    'image_url'=>'fake_image_services/category_service/e.png'
                ],
                [
                    //f
                    'name' => 'النقل',
                    'image_url'=>'fake_image_services/category_service/f.png'
                ],
                [
                    //g
                    'name' => 'تركيب إنارة',
                    'image_url'=>'fake_image_services/category_service/g.png'
                ],
                [
                    //k
                    'name' => 'طاقات بديلة',
                    'image_url'=>'fake_image_services/category_service/k.png'
                ],

                [
                    //l
                    'name' => 'ورق جدران',
                    'image_url'=>'fake_image_services/category_service/l.jpeg'
                ],
            ];
        $cc=0;
        foreach ($category_services as $category_service) {
            $cc++;
            CategoryService::insert([
                'id'=>$cc,
                'name'=>$category_service['name'],
                'image_url'=>$category_service['image_url']
            ]);
        }




        DB::table('service_providers')->delete();
        $a=[
            'fake_image_services/a/1.jpeg',
            'fake_image_services/a/2.jpeg',
            'fake_image_services/a/3.jpeg',
            'fake_image_services/a/4.jpeg',
            'fake_image_services/a/5.png',
            'fake_image_services/a/6.jpeg',
            'fake_image_services/a/7.jpeg',
            'fake_image_services/a/8.jpeg',
            'fake_image_services/a/9.jpeg',
            'fake_image_services/a/10.jpeg',
        ];
        $b=[
            'fake_image_services/b/1.jpeg',
            'fake_image_services/b/2.jpeg',
            'fake_image_services/b/3.jpeg',
            'fake_image_services/b/4.jpeg',
            'fake_image_services/b/5.jpeg',
            'fake_image_services/b/6.jpeg',
            'fake_image_services/b/7.jpeg',
            'fake_image_services/b/8.jpeg',
            'fake_image_services/b/9.jpeg',
            'fake_image_services/b/10.jpeg',
        ];
        $c=[
            'fake_image_services/c/1.png',
            'fake_image_services/c/2.jpeg',
            'fake_image_services/c/3.jpeg',
            'fake_image_services/c/4.jpeg',
            'fake_image_services/c/5.jpeg',
            'fake_image_services/c/6.jpeg',
            'fake_image_services/c/7.jpeg',
            'fake_image_services/c/8.jpeg',
            'fake_image_services/c/9.jpeg',
            'fake_image_services/c/10.jpeg',
        ];
        $d=[
            'fake_image_services/d/1.jpeg',
            'fake_image_services/d/2.jpeg',
            'fake_image_services/d/3.jpeg',
            'fake_image_services/d/5.jpeg',
            'fake_image_services/d/6.jpeg',
            'fake_image_services/d/7.jpeg',
            'fake_image_services/d/8.jpeg',
            'fake_image_services/d/9.jpeg',
            'fake_image_services/d/10.jpeg',
            'fake_image_services/d/11.jpeg',
        ];
        $e=[
            'fake_image_services/e/1.jpeg',
            'fake_image_services/e/2.jpeg',
            'fake_image_services/e/3.jpeg',
            'fake_image_services/e/4.jpeg',
            'fake_image_services/e/5.jpeg',
            'fake_image_services/e/6.jpeg',
            'fake_image_services/e/7.jpeg',
            'fake_image_services/e/8.jpeg',
            'fake_image_services/e/9.jpeg',
            'fake_image_services/e/10.jpeg',
        ];
        $f=[
            'fake_image_services/f/1.jpeg',
            'fake_image_services/f/2.jpeg',
            'fake_image_services/f/3.jpeg',
            'fake_image_services/f/4.jpeg',
            'fake_image_services/f/5.jpeg',
            'fake_image_services/f/6.jpeg',
            'fake_image_services/f/7.jpeg',
            'fake_image_services/f/8.jpeg',
            'fake_image_services/f/9.jpeg',
            'fake_image_services/f/10.jpeg',
        ];
        $g=[
            'fake_image_services/g/1.jpeg',
            'fake_image_services/g/2.jpeg',
            'fake_image_services/g/3.jpeg',
            'fake_image_services/g/4.jpeg',
            'fake_image_services/g/5.jpeg',
            'fake_image_services/g/6.jpeg',
            'fake_image_services/g/7.jpeg',
            'fake_image_services/g/8.jpeg',
            'fake_image_services/g/9.jpeg',
            'fake_image_services/g/10.jpeg',
            'fake_image_services/g/11.jpeg',
        ];
        $k=[
            'fake_image_services/k/1.jpeg',
            'fake_image_services/k/2.jpeg',
            'fake_image_services/k/3.jpeg',
            'fake_image_services/k/4.jpeg',
            'fake_image_services/k/5.jpeg',
            'fake_image_services/k/6.jpeg',
            'fake_image_services/k/7.jpeg',
            'fake_image_services/k/8.jpeg',
            'fake_image_services/k/9.jpeg',
            'fake_image_services/k/10.jpeg',
            'fake_image_services/k/11.jpeg',
            'fake_image_services/k/12.png',
        ];
        $l=[
            'fake_image_services/l/1.jpeg',
            'fake_image_services/l/2.jpeg',
            'fake_image_services/l/3.jpeg',
            'fake_image_services/l/4.jpeg',
            'fake_image_services/l/5.jpeg',
            'fake_image_services/l/6.jpeg',
            'fake_image_services/l/7.jpeg',
            'fake_image_services/l/8.jpeg',
            'fake_image_services/l/9.jpeg',
            'fake_image_services/l/10.jpeg',
            'fake_image_services/l/11.jpeg',
        ];

        $users=User::all()->pluck('id')->toArray();

        foreach ($a as $a_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,2];
            $business_image_url=$a_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($b as $b_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,3];
            $business_image_url=$b_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($c as $c_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,4];
            $business_image_url=$c_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($d as $d_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,5];
            $business_image_url=$d_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($e as $e_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,6];
            $business_image_url=$e_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($f as $f_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,7];
            $business_image_url=$f_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($g as $g_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,8];
            $business_image_url=$g_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($k as $k_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,9];
            $business_image_url=$k_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
        foreach ($l as $l_image){
            $first_name=fake('ar_JO')->firstNameMale();
            $last_name=fake('ar_JO')->lastName;
            $region_id=rand(1,Region::count());
            $secondary_address=fake('ar_JO')->address;
            $phone_number=fake('ar_JO')->phoneNumber;
            $email=fake()->email;
            $description=fake('ar_JO')->text;
            $services=[1,10];
            $business_image_url=$l_image;

            $provider=ServiceProvider::create([
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'business_image_url'=>$business_image_url,
                'region_id'=>$region_id,
                'secondary_address'=>$secondary_address,
                'phone_number'=>$phone_number,
                'email'=>$email,
                'description'=>$description,
                'isActive'=>1,
                'status'=>'accept'
            ]);
            $provider->categories()->sync($services);
            $provider->order()->create([
                'serial_number'=>$this->generate_serialnumber(Order::class),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$users[rand(0,count($users)-1)],
                'for_service_provider'=>true,
                'status'=>'posted'
            ]);
        }
    }
    public function generate_properties($user_id)
    {
        $p1=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>1,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "level_location"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/1.jpg",
                    "property_image_fake/flatAndHome/inside/2.jpg",
                    "property_image_fake/flatAndHome/outside/flat/1.jpg",
                    "property_image_fake/flatAndHome/planing/1.jfif"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>1,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "level_location"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/1.jpg",
                    "property_image_fake/flatAndHome/inside/2.jpg",
                    "property_image_fake/flatAndHome/outside/flat/1.jpg",
                    "property_image_fake/flatAndHome/planing/1.jfif"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>1,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "level_location"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/1.jpg",
                    "property_image_fake/flatAndHome/inside/2.jpg",
                    "property_image_fake/flatAndHome/outside/flat/1.jpg",
                    "property_image_fake/flatAndHome/planing/1.jfif"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>1,
                "sub_category_id"=>1,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "level_location"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/1.jpg",
                    "property_image_fake/flatAndHome/inside/2.jpg",
                    "property_image_fake/flatAndHome/outside/flat/1.jpg",
                    "property_image_fake/flatAndHome/planing/1.jfif"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>1,
                "sub_category_id"=>1,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "level_location"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/1.jpg",
                    "property_image_fake/flatAndHome/inside/2.jpg",
                    "property_image_fake/flatAndHome/outside/flat/1.jpg",
                    "property_image_fake/flatAndHome/planing/1.jfif"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>1,
                "sub_category_id"=>1,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "level_location"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/1.jpg",
                    "property_image_fake/flatAndHome/inside/2.jpg",
                    "property_image_fake/flatAndHome/outside/flat/1.jpg",
                    "property_image_fake/flatAndHome/planing/1.jfif"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p2=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>2,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/3.jpg",
                    "property_image_fake/flatAndHome/inside/4.jpg",
                    "property_image_fake/flatAndHome/inside/6.jpg",
                    "property_image_fake/flatAndHome/outside/home/1.jpg",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>2,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/3.jpg",
                    "property_image_fake/flatAndHome/inside/4.jpg",
                    "property_image_fake/flatAndHome/inside/6.jpg",
                    "property_image_fake/flatAndHome/outside/home/1.jpg",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>2,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/3.jpg",
                    "property_image_fake/flatAndHome/inside/4.jpg",
                    "property_image_fake/flatAndHome/inside/6.jpg",
                    "property_image_fake/flatAndHome/outside/home/1.jpg",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>1,
                "sub_category_id"=>2,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/3.jpg",
                    "property_image_fake/flatAndHome/inside/4.jpg",
                    "property_image_fake/flatAndHome/inside/6.jpg",
                    "property_image_fake/flatAndHome/outside/home/1.jpg",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>1,
                "sub_category_id"=>2,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/3.jpg",
                    "property_image_fake/flatAndHome/inside/4.jpg",
                    "property_image_fake/flatAndHome/inside/6.jpg",
                    "property_image_fake/flatAndHome/outside/home/1.jpg",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>1,
                "sub_category_id"=>2,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/3.jpg",
                    "property_image_fake/flatAndHome/inside/4.jpg",
                    "property_image_fake/flatAndHome/inside/6.jpg",
                    "property_image_fake/flatAndHome/outside/home/1.jpg",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p3=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>3,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "water_source"=>"well",
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/5.jpg",
                    "property_image_fake/flatAndHome/inside/7.jpg",
                    "property_image_fake/flatAndHome/outside/mazraa/1.jfif",
                    "property_image_fake/flatAndHome/outside/mazraa/2.jfif",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>3,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "water_source"=>"pipe",
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/5.jpg",
                    "property_image_fake/flatAndHome/inside/7.jpg",
                    "property_image_fake/flatAndHome/outside/mazraa/3.jfif",
                    "property_image_fake/flatAndHome/outside/mazraa/4.jfif",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>1,
                "sub_category_id"=>3,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "water_source"=>"well",
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/5.jpg",
                    "property_image_fake/flatAndHome/inside/7.jpg",
                    "property_image_fake/flatAndHome/outside/mazraa/5.jfif",
                    "property_image_fake/flatAndHome/outside/mazraa/6.jfif",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>1,
                "sub_category_id"=>3,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "water_source"=>"pipe",
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/5.jpg",
                    "property_image_fake/flatAndHome/inside/7.jpg",
                    "property_image_fake/flatAndHome/outside/mazraa/7.jfif",
                    "property_image_fake/flatAndHome/outside/mazraa/8.jfif",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>1,
                "sub_category_id"=>3,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "water_source"=>"well",
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/5.jpg",
                    "property_image_fake/flatAndHome/inside/7.jpg",
                    "property_image_fake/flatAndHome/outside/mazraa/1.jfif",
                    "property_image_fake/flatAndHome/outside/mazraa/8.jfif",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>1,
                "sub_category_id"=>3,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "levels_count"=>2,
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "water_source"=>"pipe",
                "medias"=>[
                    "property_image_fake/flatAndHome/inside/5.jpg",
                    "property_image_fake/flatAndHome/inside/7.jpg",
                    "property_image_fake/flatAndHome/outside/mazraa/7.jfif",
                    "property_image_fake/flatAndHome/outside/mazraa/2.jfif",
                    "property_image_fake/flatAndHome/planing/2.jpg"
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];

        $p4=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>4,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/1.png",
                    "property_image_fake/flatAndHome/earth/2.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>4,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/3.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>4,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/4.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>2,
                "sub_category_id"=>4,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/5.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>2,
                "sub_category_id"=>4,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/6.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>2,
                "sub_category_id"=>4,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/7.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p5=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>5,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/1.png",
                    "property_image_fake/flatAndHome/earth/2.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>5,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/3.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>5,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/4.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>2,
                "sub_category_id"=>5,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/5.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>2,
                "sub_category_id"=>5,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/6.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>2,
                "sub_category_id"=>5,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/7.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p6=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>6,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "number_of_floors_that_can_be_built"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/1.png",
                    "property_image_fake/flatAndHome/earth/2.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>6,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "number_of_floors_that_can_be_built"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/3.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>6,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "number_of_floors_that_can_be_built"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/4.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>2,
                "sub_category_id"=>6,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "number_of_floors_that_can_be_built"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/5.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>2,
                "sub_category_id"=>6,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "number_of_floors_that_can_be_built"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/6.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>2,
                "sub_category_id"=>6,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "permissible_construction_ratio"=>40,
                "number_of_floors_that_can_be_built"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/7.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p7=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>7,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "water_source"=>"rain",
                "soil_type"=>"clay",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/1.png",
                    "property_image_fake/flatAndHome/earth/2.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>7,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "water_source"=>"well",
                "soil_type"=>"sandy",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/3.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>2,
                "sub_category_id"=>7,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "water_source"=>"rain",
                "soil_type"=>"clay",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/4.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>2,
                "sub_category_id"=>7,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "water_source"=>"well",
                "soil_type"=>"sandy",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/5.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>2,
                "sub_category_id"=>7,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "water_source"=>"rain",
                "soil_type"=>"clay",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/6.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>2,
                "sub_category_id"=>7,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "water_source"=>"well",
                "soil_type"=>"sandy",
                "medias"=>[
                    "property_image_fake/flatAndHome/earth/7.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];


        $p8=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>8,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/maqtap/1.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>8,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/maqtap/2.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>8,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/maqtap/3.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>3,
                "sub_category_id"=>8,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/maqtap/4.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>3,
                "sub_category_id"=>8,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/maqtap/5.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>3,
                "sub_category_id"=>8,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/maqtap/6.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p9=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>9,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/store/1.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>9,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/store/2.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>9,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/store/3.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>3,
                "sub_category_id"=>9,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/store/4.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>3,
                "sub_category_id"=>9,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/store/1.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>3,
                "sub_category_id"=>9,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/store/2.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p10=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>10,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "ceiling_height"=>6,
                "number_of_shipping_doors"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/stroge/1.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>10,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "ceiling_height"=>6,
                "number_of_shipping_doors"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/stroge/2.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>10,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "ceiling_height"=>6,
                "number_of_shipping_doors"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/stroge/3.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>3,
                "sub_category_id"=>10,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "ceiling_height"=>6,
                "number_of_shipping_doors"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/stroge/4.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>3,
                "sub_category_id"=>10,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "ceiling_height"=>6,
                "number_of_shipping_doors"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/stroge/5.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>3,
                "sub_category_id"=>10,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "ceiling_height"=>6,
                "number_of_shipping_doors"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/stroge/6.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p11=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>11,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/showing/1.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>11,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/showing/2.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>11,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/showing/3.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>3,
                "sub_category_id"=>11,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/showing/4.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>3,
                "sub_category_id"=>11,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/showing/1.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>3,
                "sub_category_id"=>11,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "medias"=>[
                    "property_image_fake/flatAndHome/showing/2.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $p12=[
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>12,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "number_of_entries"=>2,
                "number_of_exits"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/salat/1.jpg",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>12,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "number_of_entries"=>2,
                "number_of_exits"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/salat/2.jpg",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"sale",
                "main_category_id"=>3,
                "sub_category_id"=>12,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1500000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "number_of_entries"=>2,
                "number_of_exits"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/salat/3.jpg",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],

            [
                "publication_type"=>"rent",
                "rent_type"=>"yearly",
                "main_category_id"=>3,
                "sub_category_id"=>12,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1200000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "number_of_entries"=>2,
                "number_of_exits"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/salat/4.png",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"monthly",
                "main_category_id"=>3,
                "sub_category_id"=>12,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>10000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "number_of_entries"=>2,
                "number_of_exits"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/salat/5.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
            [
                "publication_type"=>"rent",
                "rent_type"=>"daily",
                "main_category_id"=>3,
                "sub_category_id"=>12,
                "area"=>100,
                "ownership_type_id"=>5,
                "price"=>1000,
                "country_id"=>1,
                "city_id"=>1,
                "region_id"=>1,
                "secondary_address"=>"test secondary address",
                "features"=>[1,3],
                "directions"=>[1],
                "description"=>"test description",
                "rooms"=>[
                    [
                        "type_id"=>1,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>2,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>3,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>4,
                        "number"=>1,
                    ],
                    [
                        "type_id"=>5,
                        "number"=>1,
                    ]
                ],
                "pledge_type_id"=>1,
                "number_of_entries"=>2,
                "number_of_exits"=>2,
                "medias"=>[
                    "property_image_fake/flatAndHome/salat/6.jfif",
                ],
                "ownership_papers"=>[
                    "Upload/properties_ownership_papers/1.jpg",
                    "Upload/properties_ownership_papers/1.jpg"
                ]
            ],
        ];
        $u=User::where('id',$user_id)->first();
        $package_id=1;
        $package=AdvertisingPackage::where('id',$package_id)->first();
        $subscription_start_date=Carbon::now()->toDate();
        $subscription_end_date=Carbon::now()->addDays($package->validity_period)->toDate();
        $ss=$u->subscribes()->create([
            'subscription_start_date'=>$subscription_start_date,
            'subscription_end_date'=>$subscription_end_date,
            'advertisements_count'=>1000,
            'advertising_package_id'=>$package_id
        ]);

        $subscribe=$u->subscribes()->first();


        foreach ($p1 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','level_location'),
                'value'=>$item['level_location'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p2 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','levels_count'),
                'value'=>$item['levels_count'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p3 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','levels_count'),
                'value'=>$item['levels_count'],
            ]);
            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','water_source'),
                'value'=>$item['water_source'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p4 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','permissible_construction_ratio'),
                'value'=>$item['permissible_construction_ratio'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p5 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p6 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','permissible_construction_ratio'),
                'value'=>$item['permissible_construction_ratio'],
            ]);
            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','number_of_floors_that_can_be_built'),
                'value'=>$item['number_of_floors_that_can_be_built'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p7 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','water_source'),
                'value'=>$item['water_source'],
            ]);
            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','soil_type'),
                'value'=>$item['soil_type']
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p8 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p9 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p10 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','ceiling_height'),
                'value'=>$item['ceiling_height'],
            ]);
            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','number_of_shipping_doors'),
                'value'=>$item['number_of_shipping_doors'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p11 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
        foreach ($p12 as $item){
            $serial_number=$this->generate_serial_number(Property::class,1);
            $property=new Property();
            $property->serial_number=$serial_number;
            $publication_type=$item['publication_type'];
            $price=$item['price'];
            $price_history=['price'=>intval($price),'history'=>[]];
            $property->price_history=$price_history;
            $property->publication_type=$publication_type;
            if ($publication_type==='sale'){
                $property->sale_price=$price;
            }elseif ($publication_type==='rent'){
                $rent_type=$item['rent_type'];
                $rent_price=['type'=>$rent_type,'price'=>intval($price)];
                $property->rent_price=$rent_price;
            }
            $property->area=$item['area'];
            $property->age=rand(1,9);
            $property->secondary_address=$item['secondary_address'];
            $property->region_id=$item['region_id'];
            $property->category_id=$item['sub_category_id'];
            $property->ownership_type_id=$item['ownership_type_id'];
            if (isset($item['pledge_type_id'],$item)){
                $property->pledge_type_id=$item['pledge_type_id'];
            }
            $property->status="active";
            $property->save();

            foreach ($item['medias'] as $media){
                $property->medias()->create([
                    'url'=>$media,
                    'type'=>$this->after('.',$this->after_last('/',$media)),
                    'size'=>rand(10,100000)
                ]);
            }
            foreach ($item['ownership_papers'] as $paper){
                $property->ownership_papers()->create([
                    'url'=>$paper
                ]);
            }
            if (isset($item['rooms'],$item)){
                $rooms=$item['rooms'];
                foreach ($rooms as $room) {
                    $room_type_id=$room['type_id'];
                    $room_number=$room['number'];
                    $property->rooms()->create([
                        'count'=>$room_number,
                        'room_type_id'=>$room_type_id
                    ]);
                }
            }
            $directions=$item['directions'];
            $property->directions()->sync($directions);
            $features=$item['features'];
            foreach ($features as $feature) {
                $property->features()->create([
                    'feature_id'=>$feature
                ]);
            }

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','number_of_entries'),
                'value'=>$item['number_of_entries'],
            ]);

            $property->detailed_attributes()->create([
                'key'=>Str::replace('_',' ','number_of_exits'),
                'value'=>$item['number_of_exits'],
            ]);
            $property->advertisement()->create([
                'advertisement_start_date'=>Carbon::now()->toDate(),
                'advertisement_end_date'=>Carbon::now()
                    ->addDays($subscribe->advertising_package
                        ->validity_period_per_advertisement)
                    ->toDate(),
                'subscribe_id'=>$subscribe->id,
                'active'=>true,
                'classification_id'=>1
            ]);
            $subscribe->used_advertisements_count+=1;
            $subscribe->save();
            $property->order()->create([
                'serial_number'=>$this->generate_serial_number(Order::class,1),
                'date'=>Carbon::now()->toDate(),
                'user_id'=>$u->id,
                'status'=>'posted',
                'classification_id'=>1
            ]);
        }
    }

    public function relationship_exists($firstId,$secondId,$relationshipName,$model)
    {
        $exists = $model::whereHas($relationshipName, function ($query) use ($secondId) {
            $query->where('id', $secondId);
        })->where('id', $firstId)->exists();
        return $exists;
    }
    public function get_all_rules()
    {
//        $properties=[
//            'property_name' => ['required', 'string'],
//            'property_price' => ['required', 'numeric']
//        ];
//        $cars=[
//            'car_type' => ['required', 'string'],
//            'car_price' => ['required', 'numeric'],];
//        return array_merge($properties,$cars,$cars);

//        $attributes=[
//            [
//                'attribute_name'=>'property_name',
//                'rules'=>['required','string']
//            ],
//            [
//                'attribute_name'=>'property_price',
//                'rules'=>['required','numeric']
//            ],
//            [
//                'attribute_name'=>'car_type',
//                'rules'=>['required','string']
//            ],
//            [
//                'attribute_name'=>'car_price',
//                'rules'=>['required','numeric']
//            ]
//        ];
        $attributes=collect(ConfigAttributesResource::collection(ConfigAttribute::select(['attribute_name','rules'])->get()))->toArray();
        $rules=[];
        foreach ($attributes as $attribute){
            $rules[$attribute['attribute_name']]=$attribute['rules'];
        }
        return $rules;


    }
    public function generate_serial_number($model,$classification_id)
    {
        $classification=Classification::where('id',$classification_id)->first();
        $classification_name=$classification->name;
        $fist_char=Str::upper(Str::substr($classification_name,0,1));
        do {
            $randomNumber = mt_rand(1000000, 9999999);
            $serial_number =$fist_char . '_' . $randomNumber;
        } while ($model::where('serial_number',$serial_number)->exists());
        return $serial_number;
    }
    public function generate_serialnumber($model)
    {
        $model_name=$this->after_last('\\',$model);
        $fist_char=Str::upper(Str::substr($model_name,0,1));
        do {
            $randomNumber = mt_rand(1000000, 9999999);
            $serial_number =$fist_char . '_' . $randomNumber;
        } while ($model::where('serial_number',$serial_number)->exists());
        return $serial_number;
    }

    public function config_attributes($classification_id,$category_id=null)
    {

//        $attributes=[
//            [
//                'attribute_name'=>'property_name',
//                'rules'=>['required','string'],
//                'is_used'=>false,
//                'classification_id'=>1
//            ],
//            [
//                'attribute_name'=>'property_price',
//                'rules'=>['required','numeric'],
//                'is_used'=>true,
//                'classification_id'=>1
//            ],
//            [
//                'attribute_name'=>'car_type',
//                'rules'=>['required','string'],
//                'is_used'=>true,
//                'classification_id'=>2
//            ],
//            [
//                'attribute_name'=>'car_price',
//                'rules'=>['required','numeric'],
//                'is_used'=>true,
//                'classification_id'=>2
//            ]
//        ];
//
//        if ($category_id !=null)
//        {
//            return array_filter($attributes,function($attribute)use ($classification_id,$category_id){
//                return $attribute['classification_id']===$classification_id&&
//                    $attribute['category_id']===$category_id&&
//                    $attribute['is_required'];
//            });
//        }
//        return array_filter($attributes,function($attribute)use ($classification_id){
//            return $attribute['classification_id']===$classification_id&&$attribute['is_used'];
//        });

        if ($category_id !=null){
            $a1= collect(ConfigAttributesResource::collection(ConfigAttribute::where('category_id',$category_id)->where('classification_id',$classification_id)->where('is_required',true)->get()))->toArray();
            $a2= collect(ConfigAttributesResource::collection(ConfigAttribute::where('category_id',null)->where('classification_id',$classification_id)->where('is_required',true)->get()))->toArray();
            return array_merge($a1,$a2);
        }
        return collect(ConfigAttributesResource::collection(ConfigAttribute::where('classification_id',$classification_id)->where('is_required',true)->get()))->toArray();
    }
    public function switch_classifications($classification_id)
    {
//        $classification_name=Classification::find($classification_id)->name;
//        switch ($classification_name) {
//            case 'properties':
//            {
//                return [
//                    'email'=>['required','exists:users,email'],
//                ];
//                break;
//            }
//            default:
//                return [];
//                break;
//        }
//        $classification_name=Classification::find($classification_id)->name;
        $a=$this->config_attributes($classification_id);
        $attributes=[];
        foreach($a as $attribute){
            $attributes[$attribute['attribute_name']]=$attribute['rules'];
        }
        return  $attributes;
        switch ($classification_id) {
            case 1:
            {
                return [
                    'property_name'=>['required','string'],
                    'property_price'=>['required','numeric'],
                ];
                break;
            }
            case 2:
            {
                return [
                    'car_type'=>['required','string'],
                    'car_price'=>['required','numeric'],
                ];
                break;
            }
            default:
                return [];
                break;
        }
    }
    public function is_contains_array($array,$value)
    {
        foreach ($array as $item)
        {
            if (Str::contains($item,$value))
            {
                return true;
            }
        }
        return false;
    }
    public function all_payments_completed($employee)
    {
        if (collect($employee->my_tasks()->get())->every(function ($payment){
            return $payment['is_payment']===1;
        })){
            return 1;
        }
        return 0;
    }
    public function number_cost_hours($employee)
    {
        $totalHours=0;
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        $array=TaskResource::collection($employee->my_tasks()->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('is_payment',0)->get());

        $hours=[];
        foreach ($array as $item) {
            $hoursDifference = $this->calculateHoursDifference($item['start_time'], $item['end_time']);
            $hours[]=['task'=>$item,'hours'=>$hoursDifference];
            $totalHours += $hoursDifference;
        }
//            return [$totalHours,$hours];
        return ['number'=>$totalHours,'cost'=>$totalHours*Admin::where('id',1)->first()->cost_per_hour];
    }

    public function calculateHoursDifference($startTime, $endTime) {
//        $startTime =Cc
//            ($startTime);
//        return Carbon::createFromFormat('h:i A',$endTime)->diffInHours(Carbon::createFromFormat('h:i A',$startTime));
        return Carbon::createFromFormat('h:i A', $startTime)->diffInHours(Carbon::createFromFormat('h:i A', $endTime));
        $endTime = strtotime($endTime);

        $hoursDifference = ($endTime - $startTime) / 3600;
        return $hoursDifference;
    }
    public function generateRandomObjects($count) {
        $objects = [];
        for ($i = 0; $i < $count; $i++) {
            // تحديد نطاق زمني عشوائي (يمكن تعديله حسب الحاجة)
            $startDate = new DateTime('2024-09-01');
            $endDate = new DateTime(Carbon::now()->format('Y-m-d'));
            $interval = $endDate->diff($startDate);
            $randomDay = rand(0, $interval->days);
            $randomTime = rand(0, 86399); // ثواني في اليوم

            // توليد تاريخ ووقت عشوائي
            $randomDate = $startDate->add(new DateInterval('P' . $randomDay . 'D'));
            $randomDate->setTime(0, 0, $randomTime);

            // توليد وقت بداية ونهاية عشوائي
            $startTime = $randomDate->format('h:i A');
            $endTime = $randomDate->add(new DateInterval('PT2H'))->format('h:i A'); // إضافة ساعتين
            $is_payments=[0,1];

            $object = [
                "id" => $i + 1,
                "address" => "test Address",
                "start_time" => $startTime,
                "end_time" => $endTime,
                "date" => $randomDate->format('Y-m-d'),
                "is_payment" =>0
//                    $is_payments[rand(0,1)]
            ];

            $objects[] = $object;
        }

        return $objects;
    }



    public function wasemd($id)
    {
        $filename = public_path('wasem.json');
//decode to json as associative array
        $data = json_decode(file_get_contents($filename),true);
        $decrypted_data = $this->decrypt_json($data);
        $dd=json_decode($decrypted_data);
        return $this->wasema();
        return $this->wasemid($id);
        $indexToRemove = array_search($this->wasemid($id), $dd);
        if ($indexToRemove !== false) {
            array_splice($dd, $indexToRemove, 1);
        }
        return $indexToRemove;
    }
    public function wasemid($id)
    {
        $filename = public_path('wasem.json');
//decode to json as associative array
        $data = json_decode(file_get_contents($filename),true);

        if (empty($data))
        {
            return $this->returnError(00,"There are no saved items.");
        }
        $decrypted_data = $this->decrypt_json($data);
        $dd=json_decode($decrypted_data);
                $objects =$dd;

        $idToFind = $id; // Replace with the desired ID

        $filteredObject = array_filter($objects, function ($object) use ($idToFind) {
            return $object->id === $idToFind;
        });

        if (!empty($filteredObject)) {
            $object = array_values($filteredObject)[0];
            // Access the object's properties here
            return $object; // Output: "Alice"
        } else {
            return $this->returnError(000,"Object with ID [ ".$idToFind." ] not found.");
        }
    }
    public function wasema()
    {
        $filename = public_path('wasem.json');
//decode to json as associative array
        $data = json_decode(file_get_contents($filename),true);

        if (empty($data))
        {
            $data=[];
            return $data;
        }
        $decrypted_data = $this->decrypt_json($data);
        $dd=json_decode($decrypted_data);
        return $dd;
    }
    public function wasems($employee)
    {
        $filename = public_path('wasem.json');
//decode to json as associative array
        $data = json_decode(file_get_contents($filename),true);

        if (empty($data))
        {
            $data = [];
            array_push($data,$employee);
            $json_data=json_encode($data);
            $encrypted_data = $this->encrypt_json($json_data, "wsh2021105");
            file_put_contents($filename, json_encode($encrypted_data));
            return true;
        }
        $decrypted_data = $this->decrypt_json($data);
        $dd=json_decode($decrypted_data);
        array_push($dd,$employee);
        $json_data1=json_encode($dd);
        $encrypted_data1 = $this->encrypt_json($json_data1, "wsh2021105");
        file_put_contents($filename, json_encode($encrypted_data1));
        return true;


    }
    function decrypt_json($encrypted_data) {
        // Extract encrypted data, IV, and key from the combined string
        $encrypted_data_with_iv_key = base64_decode($encrypted_data);
        $encrypted_data_length = strlen($encrypted_data_with_iv_key) - 32 - 16;
        $encrypted_data = substr($encrypted_data_with_iv_key, 0, $encrypted_data_length);
        $iv = substr($encrypted_data_with_iv_key, $encrypted_data_length, 16);
        $key = substr($encrypted_data_with_iv_key, $encrypted_data_length + 16);

        // Decrypt the JSON data
        $decrypted_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);

        return $decrypted_data;
    }

    function encrypt_json($json_data, $password) {
        // Generate a random encryption key
        $key = openssl_random_pseudo_bytes(32);

        // Create an encryption context
        $ivlen = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($ivlen);
        $options = 0;

        // Encrypt the JSON data
        $encrypted_data = openssl_encrypt($json_data, 'aes-256-cbc', $key, $options, $iv);

        // Combine the encrypted data, IV, and key for storage
        $encrypted_data_with_iv_key = base64_encode($encrypted_data . $iv . $key);

        return $encrypted_data_with_iv_key;
    }
    public function replace_key($array, $old_key, $new_key) {
        $keys = array_keys($array);
        if (false === $index = array_search($old_key, $keys, true)) {
            throw new Exception(sprintf('Key "%s" does not exist', $old_key));
        }
        $keys[$index] = $new_key;
        return array_combine($keys, array_values($array));
    }
    public function testStoreNotifications($user_id,$title, $data, $type, $description)
    {
        $notify = Notification::create([
            'title' => $title,
            'type' => $type,
            'data' => json_encode($data),
            'is_read' => false,
            'description' => $description,
        ]);

        UserNotification::create([
            'user_id' => $user_id,
            'notification_id' => $notify->id
        ]);

        echo nl2br("Done *\n\n");

    }
    public function getAdmin(int $id){
        $region =Region::where('id',$id)
            ->with('branchs.branch.manager.employee.account_information.device_tokens')
            ->whereHas('branchs')
        ;
        if ($region->exists())
        {
            $user = $region->first()->branchs()->first()->branch->manager->employee->account_information;
            if($user){
                return [$user];
            }
        }

        $user =Role::where('name','Super_Admin')->with('users.device_tokens')->first();
        return  $user->users;
    }





    public function checkKeyExists($array, $key, $value) {
        foreach ($array as $item) {
            if ($item[$key]==$value) {
                return true;
            }
        }
        return false;
    }




//    public function returnDataOfTraining($type_media,$key, $value, $msg = "")

//******************************* Image Functions ***************************************************

    public function UploadeImage($Folder, $Image)
    {
        if (!is_null($Image)) {
            $Folde_Name = "";
            $Folde_Name = $Folder;
            $path = public_path('Upload/' . $Folde_Name . '/');
            !is_dir($path) && mkdir($path, 0777, true);
            $number_r = Random::generate(6);
            $imageName = $number_r . '.' . $Image->extension();
            $im = $Image->move($path, $imageName);
            $image_url = "/Upload/" . $Folde_Name . "/" . $imageName;
            return $image_url;
        }
        return null;
    }

    public function updateImage($Folder, $model, $image)
    {
        $ob = $model::where('id', $image['id'])->first();
        Storage::delete(url($ob->url));
        if (isset($image['file']) && $image['file']->isValid()) {
            $ob->update(['url' => $this->UploadeImage($Folder, $image['file'])]);
            return;
        }
        $ob->delete();
    }

    public function deleteImage($image)
    {
//        return $image;

        if (!$image)
        {
            return $this->returnError(00,'هناك شيء خاطئ');
        }
        Storage::delete(url($image->url));
        $image->delete();
        return $this->returnSuccessMessage(trans('messages.Delete'));
    }

//**********************************************************************************************


//*********************** Function Reply On Request *******************************************

    public function reply_on_request($req, $reply)
    {
        try {
            DB::beginTransaction();
            $req->replies()->create([
                'reply_text' => $reply
            ]);
            DB::commit();
            return $this->returnSuccessMessage(trans('messages.success'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->returnError(787, $exception->getMessage());
        }

    }



//**********************************************************************************************


//*********************** Function String *******************************************
    public function strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    }

    public function after($t, $inthat)
    {
        if (!is_bool(strpos($inthat, $t)))
            return substr($inthat, strpos($inthat, $t) + strlen($t));
    }

    public function after_last($t, $inthat)
    {
        if (!is_bool($this->strrevpos($inthat, $t))) {
            return substr($inthat, $this->strrevpos($inthat, $t) + strlen($t));
        }
    }

    public function before($t, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $t));
    }

    public function before_last($t, $inthat)
    {
        return substr($inthat, 0, $this->strrevpos($inthat, $t));
    }

    public function between($t, $that, $inthat)
    {
        return $this->before($that, $this->after($t, $inthat));
    }

    public function between_last($t, $that, $inthat)
    {
        return $this->after_last($t, $this->before_last($that, $inthat));
    }
//**********************************************************************************************


//************************** Function Language ***********************************************
    public function language_App()
    {
        $path = public_path('language.json');
        $data = json_decode(File::get($path), true);
        $lang = $data['language'];
        App::setLocale($lang);
    }

    public function ChangeLanguage($lang)
    {
//        $path = base_path('lang/language.json');
//        $data = json_decode(File::get($path), true);
//        $data['language'] = $lang;
//        File::put($path, json_encode($data));
        session()->put('language',$lang);
    }

    public function Language()
    {
        $path = public_path('language.json');
        $data = json_decode(File::get($path), true);
        $lang = $data['language'];
        return $this->returnData('language', $lang, '');

    }

    public function getLanguageApplication()
    {
//        $path = base_path('lang/language.json');
//        $data = json_decode(File::get($path), true);
//        $lang = $data['language'];
//        return $lang;
        if (session('language')) {
            $language = session('language');
        } elseif (config('environment_system.primary_language')) {
            $language = config('environment_system.primary_language');
        }
        return isset($language)?$language:"ar";
    }

//**********************************************************************************************

    public function RoleAssignToUser($user_id, $Role)
    {
        $user = User::find($user_id);

        $user->assignRole($Role);
        $user->givePermissionTo($Role->permissions);
    }

    public function senNotification($title, $body)
    {
        //$SERVER_API_KEY ='AAAAsLxtfnw:APA91bGZD0LEoYj8jIqD-vbjFjvVW1Fn3FFmAfflvh-I7TGOt4QxIxz6FY2FRhbnHF0o6tzeA52vsfqQOY4RTgCE1ZAfu8NpzN8oKslurYrMBh71Gci7RbO4qelpqoo7NfcrqSbGtlkp';
        // $SERVER_API_KEY ='AAAAFvveZVc:APA91bE2WDIZ251GszccUxV1kuJ-PQ3reVbnS6_YG_rYM9RdSynZBAe9_3Y5uMl496MiRR_GmporTRfopUvDKFhAsjJ-PybIE44CA19SXuOcqx7-95mQ6y_G5eG1rB-w2izkD2TvWb7f';
        $SERVER_API_KEY = "ya29.c.c0ASRK0GYpLNK6CnnGBKaz9juTQn1V5aS8jB9ca2o5ZMPU1QZOtP85vmjJ_cBiogrZVVVt7raRlRodoMY0WMSPRxCea1y_ET53BhhoregP38tpY0atDipwH6GSGf1nm34MdVko8boap1Or01MfgEx0det_ioR4yONiDRHEAZ_Z9quRToUpJgLUfB_gSUaQPvPxRdokMtrNtfLuTqW4X8IIr5Ayary0HQJLrl2T1SgsP1ll3fJRsPstCN8UIn_C9FdvANrx24u9MaJYd1jUDDmtJsBqwemTGAW3Be70BASiPhwWLQGvDzzAJwFv0MUD5mqRQePV1beW0GxDVcZBAqAUlZd-ANHN8bOXODcPlR1Sor3pI4A_nuTweliyZ3cWT389P-bbcjsMawU7jaXtksF4tpz6ezypjujJg4rwqJ1fBU1lystSl6hSi7XsijhBo14o8eXlxW306h2hxxq-xcQ87Mm0Rk1pj8RqQkspF-a7o1RBhZ9i1wIMo-bc2YMbFzm-BbYzguzWmx-zg7YSb8mxvYiQ0blr1Xb51vw88JaqpRz1effRa1mV-RwcOtkXn2jj6jX7vYqXbJj4t8zSqR3UpvtOzumz_BIf6XkY_BtB5podoQVbv6de7zYR7w8avmrWzu2br3XBRsW55pc7VgvY7a9a42594efywRSnaflhtR3vxFdQS8b3q0nhzm1U5dUtR0vMZ7f45uZ2vplokla_wizlrvqUt5W62R0pwbxUBqrxzRbBpbFFlX-iSVQb0VIt_Fz9xSeSZ4eny6Om0abF_g7JZb_30R5gvbp-h9zfffIM7wszwzVpk9_ImleoVwva2iUYI1XVZkrl2pJYiZx2hhvs6v-QlggwxVxz74m2q_hUF_74_tnjqzSm820Rm-V3eryM9QBZQXMoWxBF0J2QdVebh58I8p0oqu0WY3iy0nhX5ixbRt9rm132p-iMZd1aXRUt2zkuSsly4apwV923ttpr76f8ivUB2ziw9326";
//            "AIzaSyBcMCUmoG80q9shIQN7MEHWUMsqqAveBDk";
//            "AAAAFvveZVc:APA91bE2WDIZ251GszccUxV1kuJ-PQ3reVbnS6_YG_rYM9RdSynZBAe9_3Y5uMl496MiRR_GmporTRfopUvDKFhAsjJ-PybIE44CA19SXuOcqx7-95mQ6y_G5eG1rB-w2izkD2TvWb7f";
        //$token_1 = 'dEJItcZ8sANYR8DYRx3Kn5:APA91bFm-Q1MMeAabG2etTIqByAkzy1PnrtQqgkcGuvqF3uRrBcXm6VjajbCkv0ci5348wfv-GiTBBmTNlg44I0NGPe1SPj9QRSQOzruKVe2YiMEVe5dvwVAX47Q_NYFvtDUnRRZDskq';
//        $user = Auth::user();
        $tokenDevice = "e4J6J9bRSm-vGxNN4CBHtF:APA91bEJgaL65ZS_kXVM22iE1oc5Fip8SeXi0tp0OrTyXv8MG2gH2_sPNZ5OQ59HDZ3U5-enbui3Ymbh9jUOz9CxuUq_kyKPuA61BGFDFAIlvsHZ4nP9aziYNsQ-A8Fs1AaxOF8qdfep";
//            "eUcdTWzwSz66PiIemIdz9h:APA91bHIu8ChKL2RYW_wB8dD-kOjDiiY58rkyu2SmyJdzkIVxy7cgRJoHImwPouGviWgRG3JpYphXH9cQylz4pvCj4vMiBAmcNOtw3l5Ax1hT0RDuRVc77a6pB6YSFBVxgUUBMvTnR8J";
//            "dEJItcZ8sANYR8DYRx3Kn5:APA91bFm-Q1MMeAabG2etTIqByAkzy1PnrtQqgkcGuvqF3uRrBcXm6VjajbCkv0ci5348wfv-GiTBBmTNlg44I0NGPe1SPj9QRSQOzruKVe2YiMEVe5dvwVAX47Q_NYFvtDUnRRZDskq";
        $data = [
            "registration_ids" => [$tokenDevice],
            "notification" => [
                "title" => $title
                ,
                "body" => $body
                ,
                "sound" => "default" //required for sound on ios
            ],];
        $dataString = json_encode($data);
        $headers = ['Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json'
            ,];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // curl_close($ch);
//return $user;
        if ($status == 200) {
            $ww = Carbon::now()->format('h:i A');

//            $notification = Notification::create([
//                'title' => $title,
//                'body_text' => $body,
//                'date' => Carbon::now()->format('Y-m-d'),
//                'time' => $ww,
//                'user_id' => 1
//            ]);
            dd("oo");
        }
        return $response;
    }

    public function senNotificationWeb($title, $body)
    {
//        $path = resource_path('lang/token.json');
//        $datat = json_decode(File::get($path), true);
        $token = "dEJItcZ8sANYR8DYRx3Kn5:APA91bFm-Q1MMeAabG2etTIqByAkzy1PnrtQqgkcGuvqF3uRrBcXm6VjajbCkv0ci5348wfv-GiTBBmTNlg44I0NGPe1SPj9QRSQOzruKVe2YiMEVe5dvwVAX47Q_NYFvtDUnRRZDskq";
        //$SERVER_API_KEY ='AAAAsLxtfnw:APA91bGZD0LEoYj8jIqD-vbjFjvVW1Fn3FFmAfflvh-I7TGOt4QxIxz6FY2FRhbnHF0o6tzeA52vsfqQOY4RTgCE1ZAfu8NpzN8oKslurYrMBh71Gci7RbO4qelpqoo7NfcrqSbGtlkp';
        $SERVER_API_KEY = 'AAAAFvveZVc:APA91bE2WDIZ251GszccUxV1kuJ-PQ3reVbnS6_YG_rYM9RdSynZBAe9_3Y5uMl496MiRR_GmporTRfopUvDKFhAsjJ-PybIE44CA19SXuOcqx7-95mQ6y_G5eG1rB-w2izkD2TvWb7f';
        $token_1 = $token;
        $data = [
            "registration_ids" => [$token_1],
            "notification" => [
                "title" => $title
                ,
                "body" => $body
                ,
                "sound" => "default" //required for sound on ios
            ],];
        $dataString = json_encode($data);
        $headers = ['Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json'
            ,];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        dd($response);

    }

    public function send_notification_FCM($notification_id, $title, $message, $id, $type)
    {

        $accesstoken = env('FCM_KEY');
        echo $accesstoken;

        $URL = 'https://fcm.googleapis.com/fcm/send';


        $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },

          }';
        // print_r($post_data);die;

        $crl = curl_init();

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: ' . $accesstoken;
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($crl, CURLOPT_URL, $URL);
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

        $rest = curl_exec($crl);

        if ($rest === false) {
            // throw new Exception('Curl error: ' . curl_error($crl));
            //print_r('Curl error: ' . curl_error($crl));
            $result_noti = 0;
        } else {

            $result_noti = 1;
        }

        //curl_close($crl);
        //print_r($result_noti);die;
        return $result_noti;
    }


//****************************** Function Response ******************************************
    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => strval($errNum),
            'msg' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "", $errNum = "S000"): array
    {
        return [
            'status' => true,
            'errNum' => strval($errNum),
            'msg' => $msg
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else if ($input == "id_number")
            return 'E004';

        else if ($input == "birth_date")
            return 'E005';

        else if ($input == "agreement")
            return 'E006';

        else if ($input == "email")
            return 'E007';

        else if ($input == "city_id")
            return 'E008';

        else if ($input == "insurance_company_id")
            return 'E009';

        else if ($input == "activation_code")
            return 'E010';

        else if ($input == "longitude")
            return 'E011';

        else if ($input == "latitude")
            return 'E012';

        else if ($input == "id")
            return 'E013';

        else if ($input == "promocode")
            return 'E014';

        else if ($input == "doctor_id")
            return 'E015';

        else if ($input == "payment_method" || $input == "payment_method_id")
            return 'E016';

        else if ($input == "day_date")
            return 'E017';

        else if ($input == "specification_id")
            return 'E018';

        else if ($input == "importance")
            return 'E019';

        else if ($input == "type")
            return 'E020';

        else if ($input == "message")
            return 'E021';

        else if ($input == "reservation_no")
            return 'E022';

        else if ($input == "reason")
            return 'E023';

        else if ($input == "branch_no")
            return 'E024';

        else if ($input == "name_en")
            return 'E025';

        else if ($input == "name_ar")
            return 'E026';

        else if ($input == "gender")
            return 'E027';

        else if ($input == "nickname_en")
            return 'E028';

        else if ($input == "nickname_ar")
            return 'E029';

        else if ($input == "rate")
            return 'E030';

        else if ($input == "price")
            return 'E031';

        else if ($input == "information_en")
            return 'E032';

        else if ($input == "information_ar")
            return 'E033';

        else if ($input == "street")
            return 'E034';

        else if ($input == "branch_id")
            return 'E035';

        else if ($input == "insurance_companies")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "working_days")
            return 'E039';

        else if ($input == "insurance_companies")
            return 'E040';

        else if ($input == "reservation_period")
            return 'E041';

        else if ($input == "nationality_id")
            return 'E042';

        else if ($input == "commercial_no")
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }


}
