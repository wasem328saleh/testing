<?php

namespace Database\Seeders;

use App\Models\AdvertisingPackage;
use App\Models\Order;
use App\Models\Property;
use App\Models\SystemContactInformation;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\Has_Enumeration;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;

class ProjectSeed extends Seeder
{
    use GeneralTrait,Has_Enumeration;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        DB::table('users')->delete();
//        DB::table('admins')->delete();
//        DB::table('employees')->delete();
//        DB::table('tasks')->delete();
//
//
//        $admin_full_name="Mohammed Al Ali";
//        $admin_phone_number="+31684823444";
//        $admin_address="I don't know";
//        $admin_user_name="qassem_admin";
//        $admin_email="qassem.app2024@gmail.com";
//        $admin_password="FB/Vq4nJ31p";
//
//        $user_admin=User::create([
//            'full_name'=>$admin_full_name,
//            'phone_number'=>$admin_phone_number,
//            'address'=>$admin_address,
//            'user_name'=>$admin_user_name,
//            'email'=>$admin_email,
//            'password'=>bcrypt($admin_password),
//            'type'=>"admin",
//        ]);
//
//        $admin=Admin::create([
//            'user_id'=>$user_admin->id
//        ]);
//
//
//        $filename = public_path('wasem.json');
//        file_put_contents($filename, json_encode([]));
//
//        $faker=Factory::create('nl_NL');
//        for ($i=0;$i<11;$i++){
//            $full_name = $faker->name('male');
//            $address = $faker->address();
//            $user_name = str_replace(' ', '_', str_replace('.', '', Str::lower($full_name)));
//            $email = $user_name . "@gmail.com";
//            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
//            $password = Str::random(8, $characters);
//            do {
//                $phone_number = str_replace(' ', '', $faker->phoneNumber());
//            } while (!str_starts_with($phone_number, "+31"));
//
//            $employee_user=new User();
//            $employee_user->full_name=$full_name;
//            $employee_user->phone_number=$phone_number;
//            $employee_user->address=$address;
//            $employee_user->user_name=$user_name;
//            $employee_user->email=$email;
//            $employee_user->password=bcrypt($password);
//            $employee_user->type="employee";
//            $employee_user->save();
//            $employee=Employee::create([
//                'user_id'=>$employee_user->id
//            ]);
//
//            $tasks=$this->generateRandomObjects(20);
//            foreach ($tasks as $task)
//            {
//                $employee->my_tasks()->create([
//                    'address'=>$task['address'],
//                    'start_time'=>$task['start_time'],
//                    'end_time'=>$task['end_time'],
//                    'created_at'=>$task['date']
//                ]);
//            }
//            $data=[
//                'id'=>$employee->id,
//                'user_name'=>$user_name,
//                'password'=>$password
//            ];
//
//            $this->wasems($data);
//        }

        $prics=[
            'old_price'=>fake()->numberBetween(1000, 10000),
            'new_price'=>fake()->numberBetween(1000, 10000),
        ];
        $user_types=$this->getEnumValues(AdvertisingPackage::class,'user_type');
        $ap1=AdvertisingPackage::create([
            'title'=>'First Package',
            'price_history'=>$prics,
            'validity_period'=>fake()->numberBetween(1, 360),
            'number_of_advertisements'=>fake()->numberBetween(1, 10),
            'validity_period_per_advertisement'=>fake()->numberBetween(1, 360),
            'description'=>'Description For First Package',
            'user_type'=>$user_types[rand(0,count($user_types)-1)],
        ]);
        $ap2=AdvertisingPackage::create([
            'title'=>'Second Package',
            'price_history'=>$prics,
            'validity_period'=>fake()->numberBetween(1, 360),
            'number_of_advertisements'=>fake()->numberBetween(1, 10),
            'validity_period_per_advertisement'=>fake()->numberBetween(1, 360),
            'description'=>'Description For Second Package',
            'user_type'=>$user_types[rand(0,count($user_types)-1)],
        ]);
        $ap3=AdvertisingPackage::create([
            'title'=>'Third Package',
            'price_history'=>$prics,
            'validity_period'=>fake()->numberBetween(1, 360),
            'number_of_advertisements'=>fake()->numberBetween(1, 10),
            'validity_period_per_advertisement'=>fake()->numberBetween(1, 360),
            'description'=>'Description For Third Package',
            'user_type'=>$user_types[rand(0,count($user_types)-1)],
        ]);
        $this->call(ClassificationTableSeeder::class);
        $this->call(CategoryRealEstateSeeder::class);
        $this->call(SubscribeTableSeeder::class);
        $this->call(ConfigAttributesTableSeeder::class);
        $this->call(DirectionsTableSeeder::class);
        $this->call(FeaturesTableSeeder::class);
        $this->call(OwnersshipTypesTableSeeder::class);
        $this->call(PledgeTypesTableSeeder::class);
        $this->call(RoomTypesTableSeeder::class);
        $this->call(CategoriesServicesTableSeeder::class);
        $this->call(ServiceSeeder::class);
        $sic1=SystemContactInformation::create([
            'key'=>'Phone',
            'value'=>'+963-945541233'
        ]);
        $sic2=SystemContactInformation::create([
            'key'=>'Email',
            'value'=>'wasem.saleh328@gmail.com'
        ]);
        $users=User::all()->pluck('id')->toArray();
        foreach ($users as $user){
            $this->generate_properties($user);
        }
        $this->update_history_ads();
        $this->generate_session_services();
    }
}
