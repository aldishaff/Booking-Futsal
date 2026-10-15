<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            if (!Schema::hasColumn('transactions', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable();
            }

            if (!Schema::hasColumn('transactions', 'snap_token')) {
                $table->text('snap_token')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            if (Schema::hasColumn('transactions', 'midtrans_order_id')) {
                $table->dropColumn('midtrans_order_id');
            }

            if (Schema::hasColumn('transactions', 'snap_token')) {
                $table->dropColumn('snap_token');
            }

        });
    }
};