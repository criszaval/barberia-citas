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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('service_id')->constrained();
        $table->foreignId('staff_profile_id')->constrained();
        
        // Nullable para clientes invitados (sin cuenta)
        $table->foreignId('client_id')->nullable()->constrained('users')->onDelete('set null');

        // Datos del cliente (Se llenan siempre)
        $table->string('guest_name');
        $table->string('guest_email');
        $table->string('guest_phone');

        $table->date('appointment_date');
        $table->time('start_time');
        $table->time('end_time');
        
        $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
        $table->text('notes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
