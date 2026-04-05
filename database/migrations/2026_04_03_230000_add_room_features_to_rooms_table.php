<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'has_ac')) {
                $table->boolean('has_ac')->default(false)->after('short_price');
            }
            if (! Schema::hasColumn('rooms', 'has_fan')) {
                $table->boolean('has_fan')->default(false)->after('has_ac');
            }
            if (! Schema::hasColumn('rooms', 'has_tv')) {
                $table->boolean('has_tv')->default(false)->after('has_fan');
            }
            if (! Schema::hasColumn('rooms', 'has_fridge')) {
                $table->boolean('has_fridge')->default(false)->after('has_tv');
            }
            if (! Schema::hasColumn('rooms', 'bed_type')) {
                $table->string('bed_type', 30)->nullable()->after('has_fridge');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'bed_type')) {
                $table->dropColumn('bed_type');
            }
            if (Schema::hasColumn('rooms', 'has_fridge')) {
                $table->dropColumn('has_fridge');
            }
            if (Schema::hasColumn('rooms', 'has_tv')) {
                $table->dropColumn('has_tv');
            }
            if (Schema::hasColumn('rooms', 'has_fan')) {
                $table->dropColumn('has_fan');
            }
            if (Schema::hasColumn('rooms', 'has_ac')) {
                $table->dropColumn('has_ac');
            }
        });
    }
};
