<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('expense_categories');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['cash','momo','bank']);
            $table->string('reference')->nullable();
            $table->date('expense_date');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->index(['expense_date','category_id','payment_method']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
