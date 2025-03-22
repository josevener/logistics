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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_tech')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('isPriority')->default(0);
            $table->date('maintenance_date');
            $table->enum('maintenance_type', ['oil change', 'brake check', 'tire replacement', 'general service'])->default('general service');
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
