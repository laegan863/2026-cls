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
            // Drop the country/state/city/federal columns
            $table->dropColumn([
                'jurisdiction_country',
                'jurisdiction_state',
                'jurisdiction_city',
                'jurisdiction_federal'
            ]);
            
            // Add jurisdiction_level enum column
            $table->enum('jurisdiction_level', ['city', 'county', 'state', 'federal'])->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_types', function (Blueprint $table) {
            // Drop jurisdiction_level
            $table->dropColumn('jurisdiction_level');
            
            // Restore country/state/city/federal columns
            $table->string('jurisdiction_country')->nullable()->after('description');
            $table->string('jurisdiction_state')->nullable()->after('jurisdiction_country');
            $table->string('jurisdiction_city')->nullable()->after('jurisdiction_state');
            $table->string('jurisdiction_federal')->nullable()->after('jurisdiction_city');
        });
    }
};
