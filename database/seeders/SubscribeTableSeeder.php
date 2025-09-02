<?php

namespace Database\Seeders;

use App\Models\AdvertisingPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;

class SubscribeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscribes')->delete();
        $user =User::findOrFail(3);
        $package1_id=rand(1,AdvertisingPackage::all()->count());
        $package1=AdvertisingPackage::findOrFail($package1_id)->first();
        $user->subscribes()->create([
            'subscription_start_date'=>Carbon::now()->toDate(),
            'subscription_end_date'=>Carbon::now()->addDays($package1->validity_period)->toDate(),
            'advertisements_count'=>$package1->number_of_advertisements,
            'advertising_package_id'=>$package1_id
        ]);
        $package2_id=rand(1,AdvertisingPackage::all()->count());
        $package2=AdvertisingPackage::findOrFail($package2_id)->first();
        $user->subscribes()->create([
            'subscription_start_date'=>Carbon::now()->toDate(),
            'subscription_end_date'=>Carbon::now()->addDays($package2->validity_period)->toDate(),
            'advertisements_count'=>$package2->number_of_advertisements,
            'advertising_package_id'=>$package2_id
        ]);
        $package3_id=rand(1,AdvertisingPackage::all()->count());
        $package3=AdvertisingPackage::findOrFail($package3_id)->first();
        $user->subscribes()->create([
            'subscription_start_date'=>Carbon::now()->toDate(),
            'subscription_end_date'=>Carbon::now()->addDays($package3->validity_period)->toDate(),
            'advertisements_count'=>$package3->number_of_advertisements,
            'advertising_package_id'=>$package3_id
        ]);
    }
}
