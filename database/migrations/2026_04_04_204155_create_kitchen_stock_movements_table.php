<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kitchen_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchen_ingredient_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['purchase', 'usage', 'waste', 'adjustment']);
            $table->decimal('quantity', 10, 4); // positive for purchase/adjustment-in, negative for usage/waste/adjustment-out
            $table->decimal('unit_cost', 10, 2)->nullable(); // cost per unit at time of movement
            $table->morphs('reference'); // polymorphic: can relate to Order, MenuItem, or null for manual adjustments
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['kitchen_ingredient_id', 'type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_stock_movements');
    }
};
