<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\DiscountType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->enum(
                'discount_type',
                array_column(DiscountType::cases(), 'value')
            );

            $table->decimal('discount_value',10,2);

            $table->date('start_date');

            $table->date('end_date');

            $table->text('description')->nullable();

            $table->boolean('status')->default(true)->index();
            
            $table->index(['name', 'start_date','end_date']);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
