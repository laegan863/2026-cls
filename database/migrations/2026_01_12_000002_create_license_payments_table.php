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
        Schema::create('license_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('licenses')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Agent/Admin who created
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['draft', 'open', 'paid', 'cancelled', 'overridden'])->default('draft');
            $table->enum('payment_method', ['online', 'offline'])->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_checkout_session_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by')->nullable()->constrained('users')->onDelete('set null'); // For offline payments
            $table->foreignId('overridden_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('override_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('license_payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_payment_id')->constrained('license_payments')->onDelete('cascade');
            $table->string('label'); // e.g., "License Fee", "Processing Fee", etc.
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_payment_items');
        Schema::dropIfExists('license_payments');
    }
};
