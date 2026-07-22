<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\SubscriptionStatus;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('member_id')->constrained()->cascadeOnDelete();

        $table->foreignId('plan_id')->constrained()->cascadeOnDelete();

        $table->foreignId('offer_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->date('start_date');

        $table->date('end_date');

        $table->decimal('original_price',10,2);

        $table->decimal('discount',10,2)->default(0);

        $table->decimal('final_price',10,2)->index();

        $table->enum(
            'status',
            array_column(SubscriptionStatus::cases(), 'value')
        )->default(SubscriptionStatus::ACTIVE->value)->index();

        $table->index(['member_id', 'status']);
        $table->index(['start_date', 'end_date']);
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
