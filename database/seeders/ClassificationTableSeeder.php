<?php

namespace Database\Seeders;

use App\Models\Classification;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classifications')->delete();
        $date_at=Carbon::now()->format('Y-m-d H:i:s');
        $classifications=[
            [
                'id'             => 1,
                'name'           => 'properties',
                'created_at'     => $date_at,
                'updated_at'     => $date_at,
            ]
        ];
//        foreach ($classifications as $classification){
//            Classification::create
//        }
        Classification::insert($classifications);
    }
}
