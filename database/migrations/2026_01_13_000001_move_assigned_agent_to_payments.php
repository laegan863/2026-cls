<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Move assigned_agent_id from licenses to license_payments table
     * because each payment (renewal) can be handled by a different agent.
     */
    public function up(): void
    {
        // Add assigned_agent_id to license_payments
        Schema::table('license_payments', function (Blueprint $table) {
            $table->foreignId('assigned_agent_id')->nullable()->after('created_by')->constrained('users')->onDelete('set null');
        });

        // Migrate existing data: copy assigned_agent_id from licenses to their latest payment
        DB::statement("
            UPDATE license_payments lp
            INNER JOIN licenses l ON lp.license_id = l.id
            SET lp.assigned_agent_id = l.assigned_agent_id
            WHERE l.assigned_agent_id IS NOT NULL
        ");

        // Remove assigned_agent_id from licenses table
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['assigned_agent_id']);
            $table->dropColumn('assigned_agent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add assigned_agent_id back to licenses
        Schema::table('licenses', function (Blueprint $table) {
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->onDelete('set null');
        });

        // Migrate data back: copy from latest payment to license
        DB::statement("
            UPDATE licenses l
            INNER JOIN (
                SELECT license_id, assigned_agent_id
                FROM license_payments
                WHERE assigned_agent_id IS NOT NULL
                ORDER BY created_at DESC
            ) lp ON l.id = lp.license_id
            SET l.assigned_agent_id = lp.assigned_agent_id
        ");

        // Remove from license_payments
        Schema::table('license_payments', function (Blueprint $table) {
            $table->dropForeign(['assigned_agent_id']);
            $table->dropColumn('assigned_agent_id');
        });
    }
};
