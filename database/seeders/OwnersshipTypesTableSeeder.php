<?php

namespace Database\Seeders;

use App\Models\OwnershipType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnersshipTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ownership_types')->delete();
        $types = ['طابو أخضر',
            'حكم محكمة',
            'وكالة كاتب العدل',
            'وضع يد',
            'وصاية',
            'ورثة',
            'عقد بيع قطعي',
            'طابو أسهم',
            'طابو زراعي',
            'طابو إسكان',
            'فروغ'];
        foreach ($types as $type) {
            OwnershipType::create(['name' => $type]);
        }
    }
}
