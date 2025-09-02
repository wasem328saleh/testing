<?php

namespace Database\Seeders;

use App\Models\PledgeType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PledgeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pledge_types')->delete();
        $types = ['عادي',
            'متوسط',
            'ديلوكس',
            'سوبر ديلوكس'];
        foreach ($types as $type) {
            PledgeType::create(['name' => $type]);
        }
    }
}
