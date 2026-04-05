<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'stay_type')) {
                $table->string('stay_type', 20)->default('long')->after('room_type_id');
            }
        });

        DB::table('rooms')->whereNull('stay_type')->update(['stay_type' => 'long']);
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'stay_type')) {
                $table->dropColumn('stay_type');
            }
        });
    }
};
