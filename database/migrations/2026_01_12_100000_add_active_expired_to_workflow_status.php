<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum to add new values
        DB::statement("ALTER TABLE licenses MODIFY COLUMN workflow_status ENUM(
            'pending_validation',
            'requirements_pending',
            'requirements_submitted',
            'approved',
            'active',
            'payment_pending',
            'payment_completed',
            'completed',
            'rejected',
            'expired'
        ) DEFAULT 'pending_validation'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update any 'active' or 'expired' statuses to 'approved'
        DB::table('licenses')->whereIn('workflow_status', ['active', 'expired'])->update(['workflow_status' => 'approved']);
        
        DB::statement("ALTER TABLE licenses MODIFY COLUMN workflow_status ENUM(
            'pending_validation',
            'requirements_pending',
            'requirements_submitted',
            'approved',
            'payment_pending',
            'payment_completed',
            'completed',
            'rejected'
        ) DEFAULT 'pending_validation'");
    }
};
