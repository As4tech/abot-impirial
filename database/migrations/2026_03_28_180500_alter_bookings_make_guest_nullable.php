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
        if (! Schema::hasColumn('bookings', 'guest_id')) {
            return; // nothing to change if legacy guest_id is not present
        }

        // Drop FK if present to change nullability safely
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['guest_id']);
            });
        } catch (Throwable $e) {
            // ignore if it doesn't exist
        }

        // Make guest_id nullable using raw SQL to avoid requiring doctrine/dbal
        DB::statement('ALTER TABLE bookings MODIFY guest_id BIGINT UNSIGNED NULL');

        // Recreate FK constraint allowing nulls
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('guest_id')->references('id')->on('guests')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings') || ! Schema::hasColumn('bookings', 'guest_id')) {
            return;
        }

        // Drop FK to make column NOT NULL again
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['guest_id']);
            });
        } catch (Throwable $e) {
            // ignore
        }

        // Set NOT NULL (will fail if nulls exist)
        DB::statement('ALTER TABLE bookings MODIFY guest_id BIGINT UNSIGNED NOT NULL');

        // Restore FK with cascade as per original migration
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('guest_id')->references('id')->on('guests')->cascadeOnDelete();
        });
    }
};
