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
        Schema::create('license_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('license_payments')->onDelete('set null');
            $table->integer('renewal_number')->default(1); // 1st renewal, 2nd renewal, etc.
            $table->date('previous_expiration_date')->nullable();
            $table->date('new_expiration_date')->nullable();
            $table->string('renewal_evidence_file')->nullable();
            $table->timestamp('file_uploaded_at')->nullable();
            $table->foreignId('file_uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending_payment', 'pending_file', 'completed'])->default('pending_payment');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_renewals');
    }
};
