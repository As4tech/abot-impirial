<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('room_id')->constrained()->cascadeOnDelete();
                $table->string('rate_type')->nullable(); // daily|hourly
                $table->decimal('hourly_rate', 12, 2)->nullable();
                $table->decimal('nightly_rate', 12, 2)->nullable();
                $table->decimal('initial_charge', 12, 2)->default(0);
                $table->decimal('computed_charge', 12, 2)->nullable();
                $table->timestamp('check_in_at');
                $table->timestamp('check_out_at')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::drop('bookings');
        }
    }
};
