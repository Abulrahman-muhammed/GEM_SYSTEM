<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\DiscountType;
/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'name'=>fake()->words(2,true),

            'discount_type'=>fake()->randomElement(DiscountType::cases()),

            'discount_value'=>fake()->randomFloat(2,10,40),

            'start_date'=>now()->subDays(rand(1,10)),

            'end_date'=>now()->addDays(rand(10,60)),

            'description'=>fake()->sentence(),

            'status'=>true,

        ];
    }
}
