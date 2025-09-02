<?php

namespace Database\Factories;

use App\Models\AdvertisingPackage;
use App\Traits\Has_Enumeration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AdvertisingPackageFactory extends Factory
{
    use Has_Enumeration;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdvertisingPackage::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prics=[
            'old_price'=>fake()->numberBetween(1000, 10000),
            'new_price'=>fake()->numberBetween(1000, 10000),
        ];
        $user_types=$this->getEnumValues(AdvertisingPackage::class,'user_type');
        return [
            'title'=>fake()->title(),
            'price_history'=>$prics,
            'validity_period'=>fake()->numberBetween(1, 360),
            'number_of_advertisements'=>fake()->numberBetween(1, 10),
            'validity_period_per_advertisement'=>fake()->numberBetween(1, 360),
            'description'=>fake()->text,
            'user_type'=>$user_types[rand(0,count($user_types)-1)],
        ];
    }
}
