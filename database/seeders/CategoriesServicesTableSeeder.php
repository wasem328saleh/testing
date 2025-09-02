<?php

namespace Database\Seeders;

use App\Models\CategoryService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        CategoryService::factory()->count(3)->create();

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

        foreach ($category_services as $category_service) {
            CategoryService::create([
                'name'=>$category_service['name'],
                'image_url'=>$category_service['image_url']
            ]);
        }

    }
}
