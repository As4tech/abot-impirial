<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kitchen_ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('unit'); // e.g., kg, L, g, ml, pcs
            $table->decimal('current_stock', 10, 4)->default(0);
            $table->decimal('min_stock_level', 10, 4)->default(0);
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index('name');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kitchen_ingredients');
    }
};
