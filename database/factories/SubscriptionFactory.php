<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Offer;
use App\Enums\DiscountType;
use App\Enums\SubscriptionStatus;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plan = Plan::inRandomOrder()->first();
        $offer = $this->faker->boolean(40) ? Offer::inRandomOrder()->first() : null;

        return [
            'member_id' => Member::factory(),

            'plan_id' => $plan->id,

            'offer_id' => $offer ? $offer->id : null,

            'start_date' => now()->subDays(rand(30, 365)),

            'end_date' => now()->addDays(rand(30, 365)),

            'original_price' => $plan->price,

            'discount' => $offer
                ? ($offer->discount_type === DiscountType::PERCENTAGE
                    ? $plan->price * ($offer->discount_value / 100)
                    : $offer->discount_value)
                : 0,

            'final_price' => $plan->price - $discount,

            'status' => $this->faker->boolean(90) ? SubscriptionStatus::ACTIVE : SubscriptionStatus::EXPIRED,

            'is_freeze' => false,
        ];
    }
}
