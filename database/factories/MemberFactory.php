<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Gender;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),

            'phone' => fake()->unique()->numerify('01#########'),

            'gender' => fake()->randomElement(Gender::cases()),

            'birth_date' => fake()->dateTimeBetween('-45 years', '-16 years'),

            'address' => fake()->address(),

            'photo' => null,

            'status' => fake()->boolean(90),

            'notes' => fake()->optional()->sentence(),
        ];
    }
}
