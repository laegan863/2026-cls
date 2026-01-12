<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update enum values for renewal_status and billing_status
     */
    public function up(): void
    {
        // First, set any empty values to 'closed'
        DB::statement("UPDATE licenses SET renewal_status = 'closed' WHERE renewal_status IS NULL OR renewal_status = ''");
        DB::statement("UPDATE licenses SET billing_status = 'closed' WHERE billing_status IS NULL OR billing_status = ''");
        
        // Update renewal_status enum to include 'expired'
        DB::statement("ALTER TABLE licenses MODIFY COLUMN renewal_status ENUM('closed', 'open', 'expired') NOT NULL DEFAULT 'closed'");
        
        // Update billing_status enum to include all values
        DB::statement("ALTER TABLE licenses MODIFY COLUMN billing_status ENUM('closed', 'open', 'invoiced', 'paid', 'overridden') NOT NULL DEFAULT 'closed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE licenses MODIFY COLUMN renewal_status ENUM('closed', 'open') NOT NULL DEFAULT 'closed'");
        DB::statement("ALTER TABLE licenses MODIFY COLUMN billing_status ENUM('closed', 'open') NOT NULL DEFAULT 'closed'");
    }
};
