<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Offer;
use App\Models\Subscription;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => fake()->unique()->numerify('INV-######'),

            'member_id' => Member::factory(),

            'subscription_id' => Subscription::factory(),

            'plan_id' => Plan::factory(),

            'offer_id' => $this->faker->boolean(40) ? Offer::factory() : null,

            'amount' => fake()->numberBetween(100, 5000),

            'discount_amount' => 0,

            'net_amount' => fake()->numberBetween(100, 5000),

            'payment_date' => fake()->dateTimeThisYear(),

            'payment_method' => fake()->randomElement(PaymentMethod::cases()),

            'transaction_id' => fake()->optional()->regexify('[A-Za-z0-9]{10,20}'),

            'payment_status' => $this->faker->boolean(95) ? PaymentStatus::PAID : fake()->randomElement([PaymentStatus::PENDING, PaymentStatus::FAILED]),

            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
