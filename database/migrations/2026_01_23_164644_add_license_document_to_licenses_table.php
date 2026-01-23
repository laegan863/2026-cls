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
            $table->string('license_document')->nullable()->after('renewal_evidence_file');
            $table->string('license_document_name')->nullable()->after('license_document');
            $table->timestamp('license_document_uploaded_at')->nullable()->after('license_document_name');
            $table->foreignId('license_document_uploaded_by')->nullable()->after('license_document_uploaded_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropForeign(['license_document_uploaded_by']);
            $table->dropColumn(['license_document', 'license_document_name', 'license_document_uploaded_at', 'license_document_uploaded_by']);
        });
    }
};
