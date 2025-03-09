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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_info');
            $table->string('contact_details');
            $table->string('purpose');
            $table->string('documentation_path');
            $table->string('status')->default('pending');
            $table->text('fraud_notes')->nullable(); // AI analysis results
            $table->string('admin_status')->default('pending');
            $table->tinyInteger('approved_by')->default(0);
            $table->string('actioned_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
