<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('name')->constrained('product_categories')->nullOnDelete();
            $table->string('unit')->nullable()->after('category_id');
            $table->decimal('stock_quantity', 12, 3)->default(0)->after('unit');
            $table->decimal('cost_price', 10, 2)->nullable()->after('stock_quantity');
            $table->decimal('selling_price', 10, 2)->nullable()->after('cost_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn(['unit', 'stock_quantity', 'cost_price', 'selling_price']);
        });
    }
};
