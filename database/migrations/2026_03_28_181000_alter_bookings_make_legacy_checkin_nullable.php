<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        // Only proceed if legacy columns exist
        $hasCheckIn = Schema::hasColumn('bookings', 'check_in');
        $hasCheckOut = Schema::hasColumn('bookings', 'check_out');

        if ($hasCheckIn) {
            // Make legacy check_in nullable so new code using check_in_at doesn't fail
            DB::statement('ALTER TABLE bookings MODIFY `check_in` TIMESTAMP NULL DEFAULT NULL');
        }
        if ($hasCheckOut) {
            // Ensure legacy check_out is also nullable (idempotent)
            DB::statement('ALTER TABLE bookings MODIFY `check_out` TIMESTAMP NULL DEFAULT NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        // Revert to NOT NULL for check_in (may fail if nulls exist, so wrap in try/catch-like behavior via SQL)
        if (Schema::hasColumn('bookings', 'check_in')) {
            // Set any nulls to current timestamp before making NOT NULL to avoid failure
            DB::statement('UPDATE bookings SET `check_in` = COALESCE(`check_in`, NOW())');
            DB::statement('ALTER TABLE bookings MODIFY `check_in` TIMESTAMP NOT NULL');
        }
        if (Schema::hasColumn('bookings', 'check_out')) {
            // Keep as NULLABLE as original migration already allowed nulls; no change needed
        }
    }
};
