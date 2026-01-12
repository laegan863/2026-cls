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
            // Main workflow status
            $table->enum('workflow_status', [
                'pending_validation',      // Initial submission - awaiting agent/admin review
                'requirements_pending',    // Missing requirements - waiting for client
                'requirements_submitted',  // Client submitted requirements - awaiting review
                'approved',                // All requirements approved
                'payment_pending',         // Payment created and open
                'payment_completed',       // Payment done
                'completed',               // License process complete
                'rejected'                 // License application rejected
            ])->default('pending_validation')->after('status');
            
            // Track validation
            $table->timestamp('validated_at')->nullable()->after('workflow_status');
            $table->foreignId('validated_by')->nullable()->after('validated_at')
                ->constrained('users')->onDelete('set null');
            
            // Track approval
            $table->timestamp('approved_at')->nullable()->after('validated_by');
            $table->foreignId('approved_by')->nullable()->after('approved_at')
                ->constrained('users')->onDelete('set null');
            
            // Rejection reason if rejected
            $table->text('rejection_reason')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'workflow_status',
                'validated_at',
                'validated_by',
                'approved_at',
                'approved_by',
                'rejection_reason'
            ]);
        });
    }
};
