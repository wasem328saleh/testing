<?php

namespace Database\Seeders;

use App\Models\MerchantRegisterOrder;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    use GeneralTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::findOrFail(1)->roles()->sync(1);
        User::findOrFail(2)->roles()->sync(3);
        User::findOrFail(3)->roles()->sync(2);
//        User::findOrFail(4)->roles()->sync(2);
    }
}
