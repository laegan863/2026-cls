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
            // New store location fields
            $table->string('street_number')->nullable()->after('store_email');
            $table->string('street_name')->nullable()->after('street_number');
            $table->string('county')->nullable()->after('city');
            
            // Business entity field
            $table->string('sales_tax_id')->nullable()->after('fein');
            
            // Permit/License details
            $table->string('jurisdiction_level')->nullable()->after('jurisdiction_federal');
            $table->string('permit_number')->nullable()->after('permit_subtype');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn([
                'street_number',
                'street_name', 
                'county',
                'sales_tax_id',
                'jurisdiction_level',
                'permit_number'
            ]);
        });
    }
};
