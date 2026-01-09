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
        Schema::table('users', function (Blueprint $table) {
            $table->string('contact_no')->nullable()->after('email');
            $table->foreignId('role_id')->nullable()->after('contact_no')->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true)->after('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['contact_no', 'role_id', 'is_active']);
        });
    }
};
