<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('bookings')) {
            return; // nothing to do, fresh installs will use the create migration
        }

        // Add columns if they are missing
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'rate_type')) {
                $table->string('rate_type')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'hourly_rate')) {
                $table->decimal('hourly_rate', 12, 2)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'nightly_rate')) {
                $table->decimal('nightly_rate', 12, 2)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'initial_charge')) {
                $table->decimal('initial_charge', 12, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'computed_charge')) {
                $table->decimal('computed_charge', 12, 2)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'check_in_at')) {
                $table->timestamp('check_in_at')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'check_out_at')) {
                $table->timestamp('check_out_at')->nullable();
            }
        });

        // Backfill check_in_at / check_out_at from legacy columns if present
        $hasCheckIn = Schema::hasColumn('bookings', 'check_in');
        $hasCheckOut = Schema::hasColumn('bookings', 'check_out');
        if ($hasCheckIn) {
            // Copy values only where target is null
            DB::statement('UPDATE bookings SET check_in_at = COALESCE(check_in_at, check_in)');
        }
        if ($hasCheckOut) {
            DB::statement('UPDATE bookings SET check_out_at = COALESCE(check_out_at, check_out)');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'check_out_at')) {
                $table->dropColumn('check_out_at');
            }
            if (Schema::hasColumn('bookings', 'check_in_at')) {
                $table->dropColumn('check_in_at');
            }
            if (Schema::hasColumn('bookings', 'computed_charge')) {
                $table->dropColumn('computed_charge');
            }
            if (Schema::hasColumn('bookings', 'initial_charge')) {
                $table->dropColumn('initial_charge');
            }
            if (Schema::hasColumn('bookings', 'nightly_rate')) {
                $table->dropColumn('nightly_rate');
            }
            if (Schema::hasColumn('bookings', 'hourly_rate')) {
                $table->dropColumn('hourly_rate');
            }
            if (Schema::hasColumn('bookings', 'rate_type')) {
                $table->dropColumn('rate_type');
            }
            if (Schema::hasColumn('bookings', 'order_id')) {
                $table->dropConstrainedForeignId('order_id');
            }
        });
    }
};
