<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plans = [
            ['Monthly',30,300],
            ['Quarterly',90,800],
            ['Half Year',180,1500],
            ['Yearly',365,2800],
        ];

        [$name,$days,$price] = fake()->randomElement($plans);

        return [

            'name'=>$name,

            'price'=>$price,

            'duration_days'=>$days,

            'description'=>fake()->sentence(),

            'status'=>true,

        ];

    }
}
