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
            // Drop old jurisdiction_level and agency columns
            $table->dropColumn(['jurisdiction_level', 'agency']);
            
            // Add new jurisdiction fields
            $table->string('jurisdiction_country')->nullable()->after('description');
            $table->string('jurisdiction_state')->nullable()->after('jurisdiction_country');
            $table->string('jurisdiction_city')->nullable()->after('jurisdiction_state');
            $table->string('jurisdiction_federal')->nullable()->after('jurisdiction_city');
            $table->string('agency_name')->nullable()->after('jurisdiction_federal');
            $table->date('expiration_date')->nullable()->after('agency_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_types', function (Blueprint $table) {
            // Drop new jurisdiction fields
            $table->dropColumn([
                'jurisdiction_country',
                'jurisdiction_state',
                'jurisdiction_city',
                'jurisdiction_federal',
                'agency_name',
                'expiration_date'
            ]);
            
            // Restore old columns
            $table->enum('jurisdiction_level', ['city', 'county', 'state', 'federal'])->nullable()->after('description');
            $table->string('agency')->nullable()->after('jurisdiction_level');
        });
    }
};
