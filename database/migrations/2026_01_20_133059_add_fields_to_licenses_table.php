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
        Schema::table('licenses', function (Blueprint $table) {
            $table->string('store_name')->nullable()->after('fein');
            $table->string('store_address')->nullable()->after('store_name');
            $table->string('store_city')->nullable()->after('store_address');
            $table->string('store_state')->nullable()->after('store_city');
            $table->string('store_zip_code')->nullable()->after('store_state');
            $table->string('store_phone')->nullable()->after('store_zip_code');
            $table->string('store_email')->nullable()->after('store_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn([
                'store_name',
                'store_address',
                'store_city',
                'store_state',
                'store_zip_code',
                'store_phone',
                'store_email',
            ]);
        });
    }
};
