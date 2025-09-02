<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->delete();
        DB::table('cities')->delete();
        DB::table('regions')->delete();
        $country=Country::create(['name' =>'سوريا']);
        $city=City::create(['name' =>'حلب','country_id'=>$country->id]);
        $region=Region::create(['name' =>'حلب الجديدة','city_id'=>$city->id]);
//        Country::factory()->count(2)->create();
//        City::factory()->count(5)->create();
//        Region::factory()->count(10)->create();
    }
}
