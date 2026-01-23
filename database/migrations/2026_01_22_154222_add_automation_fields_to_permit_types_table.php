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
        Schema::table('permit_types', function (Blueprint $table) {
            $table->string('short_name')->nullable()->after('permit_type');
            $table->text('description')->nullable()->after('short_name');
            $table->enum('jurisdiction_level', ['city', 'county', 'state', 'federal'])->nullable()->after('description');
            $table->string('agency')->nullable()->after('jurisdiction_level');
            
            // Renewal Settings
            $table->boolean('has_renewal')->default(false)->after('agency');
            $table->integer('renewal_cycle_months')->nullable()->after('has_renewal');
            $table->json('reminder_days')->nullable()->after('renewal_cycle_months'); // [60, 30, 15]
            
            // Fees Associated
            $table->decimal('government_fee', 10, 2)->nullable()->after('reminder_days');
            $table->decimal('cls_service_fee', 10, 2)->nullable()->after('government_fee');
            $table->decimal('city_county_fee', 10, 2)->nullable()->after('cls_service_fee');
            $table->decimal('additional_fee', 10, 2)->nullable()->after('city_county_fee');
            $table->string('additional_fee_description')->nullable()->after('additional_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_types', function (Blueprint $table) {
            $table->dropColumn([
                'short_name',
                'description',
                'jurisdiction_level',
                'agency',
                'has_renewal',
                'renewal_cycle_months',
                'reminder_days',
                'government_fee',
                'cls_service_fee',
                'city_county_fee',
                'additional_fee',
                'additional_fee_description',
            ]);
        });
    }
};
