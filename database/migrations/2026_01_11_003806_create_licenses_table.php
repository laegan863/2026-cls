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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            
            // Client Information
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('primary_contact_info')->nullable();
            
            // Business Entity
            $table->string('legal_name')->nullable();
            $table->string('dba')->nullable();
            $table->string('fein')->nullable();
            
            // Store / Location
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            
            // Permit / License Details
            $table->string('permit_type')->nullable();
            $table->string('permit_subtype')->nullable();
            $table->string('jurisdiction_country')->nullable();
            $table->string('jurisdiction_state')->nullable();
            $table->string('jurisdiction_city')->nullable();
            $table->string('jurisdiction_federal')->nullable();
            $table->string('agency_name')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('renewal_window_open_date')->nullable();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('renewal_status')->nullable();
            $table->string('billing_status')->nullable();
            $table->string('submission_confirmation_number')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
