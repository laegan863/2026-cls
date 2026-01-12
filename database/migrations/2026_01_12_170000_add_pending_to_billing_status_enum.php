<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add 'pending' to billing_status enum
     */
    public function up(): void
    {
        // Update billing_status enum to include 'pending'
        DB::statement("ALTER TABLE licenses MODIFY COLUMN billing_status ENUM('closed', 'pending', 'open', 'invoiced', 'paid', 'overridden') NOT NULL DEFAULT 'closed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update any 'pending' values to 'closed'
        DB::statement("UPDATE licenses SET billing_status = 'closed' WHERE billing_status = 'pending'");
        
        // Remove 'pending' from enum
        DB::statement("ALTER TABLE licenses MODIFY COLUMN billing_status ENUM('closed', 'open', 'invoiced', 'paid', 'overridden') NOT NULL DEFAULT 'closed'");
    }
};
