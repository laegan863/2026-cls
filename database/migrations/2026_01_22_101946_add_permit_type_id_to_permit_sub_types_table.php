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
        Schema::table('permit_sub_types', function (Blueprint $table) {
            $table->foreignId('permit_type_id')->nullable()->after('id')->constrained('permit_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_sub_types', function (Blueprint $table) {
            $table->dropForeign(['permit_type_id']);
            $table->dropColumn('permit_type_id');
        });
    }
};
