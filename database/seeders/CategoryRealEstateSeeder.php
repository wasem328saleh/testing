<?php

namespace Database\Seeders;

use App\Models\PropertyMainCategory;
use App\Models\PropertySubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryRealEstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('property_main_categories')->delete();
        DB::table('property_sub_categories')->delete();
        $categories = [

            'سكني ' => [
                'شقة' ,
                'منزل' ,
                'مزرعة' ,
            ],
            'أراضي' => [
                'تجارية' ,
                'صناعية' ,
                'سكنية' ,
                'زراعية' ,
            ],
            'تجاري' => [
                'مكتب' ,
                ' محل تجاري' ,
                'مستودع' ,
                'معرض' ,
                'صالة' ,
            ],
        ];
        foreach ($categories as $categoryName => $propertyTypes) {
            $category = PropertyMainCategory::create(['name' => $categoryName]);

            foreach ($propertyTypes as $propertyTypeName) {
                $propertyType = new PropertySubCategory();
                $propertyType->name = $propertyTypeName;
                $category->sub_categories()->save($propertyType);
            }
        }
    }
}
