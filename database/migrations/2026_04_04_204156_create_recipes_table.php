<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('kitchen_ingredient_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_required', 10, 4);
            $table->string('unit'); // same unit as ingredient
            $table->timestamps();
            
            $table->unique(['menu_item_id', 'kitchen_ingredient_id']);
            $table->index('menu_item_id');
            $table->index('kitchen_ingredient_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
