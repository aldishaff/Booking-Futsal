<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired'
            ])->default('pending')->change();

            $table->string('snap_token')->nullable();

            $table->string('midtrans_order_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropColumn([
                'snap_token',
                'midtrans_order_id'
            ]);
        });
    }
};