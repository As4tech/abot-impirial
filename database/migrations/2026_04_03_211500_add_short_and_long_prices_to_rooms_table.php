<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'long_price')) {
                $table->decimal('long_price', 10, 2)->nullable()->after('price');
            }

            if (! Schema::hasColumn('rooms', 'short_price')) {
                $table->decimal('short_price', 10, 2)->nullable()->after('long_price');
            }
        });

        DB::statement('UPDATE rooms SET long_price = COALESCE(long_price, price), short_price = COALESCE(short_price, price)');
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'short_price')) {
                $table->dropColumn('short_price');
            }

            if (Schema::hasColumn('rooms', 'long_price')) {
                $table->dropColumn('long_price');
            }
        });
    }
};
