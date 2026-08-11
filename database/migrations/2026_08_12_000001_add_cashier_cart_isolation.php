<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->index('user_id');
        });

        // Clear leftover shared cart rows — they cannot be safely attributed.
        DB::table('pos')->delete();

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cashier_id')->nullable()->after('customer_id');
            $table->index('cashier_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['cashier_id']);
            $table->dropColumn('cashier_id');
        });

        Schema::table('pos', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
