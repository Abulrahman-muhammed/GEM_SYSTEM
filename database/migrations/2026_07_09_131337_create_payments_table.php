<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentMethod;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('subscription_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->decimal('amount',10,2);

        $table->enum(
            'method',
            array_column(PaymentMethod::cases(), 'value')
        )->index();

        $table->date('payment_date');

        $table->text('notes')->nullable();
        
        $table->index(['subscription_id', 'amount']);

        $table->index(['payment_date','method']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
