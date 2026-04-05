<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('rooms', 'room_type_id')) {
                $table->foreignId('room_type_id')->nullable()->after('room_number')->constrained('room_types')->nullOnDelete();
            }
        });

        $distinctTypes = DB::table('rooms')
            ->select('type')
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->pluck('type');

        foreach ($distinctTypes as $typeName) {
            $roomTypeId = DB::table('room_types')->where('name', $typeName)->value('id');

            if (! $roomTypeId) {
                $samplePrice = DB::table('rooms')->where('type', $typeName)->avg('price') ?? 0;

                $roomTypeId = DB::table('room_types')->insertGetId([
                    'name' => $typeName,
                    'description' => null,
                    'base_price' => $samplePrice,
                    'capacity' => 1,
                    'amenities' => null,
                    'image_url' => null,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('rooms')
                ->where('type', $typeName)
                ->update(['room_type_id' => $roomTypeId]);
        }
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'room_type_id')) {
                $table->dropConstrainedForeignId('room_type_id');
            }
        });
    }
};
