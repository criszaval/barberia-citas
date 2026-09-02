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
    Schema::create('services', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->integer('duration_minutes'); // Duración en minutos (ej: 30, 45, 60)
        $table->decimal('price', 8, 2);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    // Tabla pivote: Relaciona qué servicios realiza cada empleado
    Schema::create('service_staff', function (Blueprint $table) {
        $table->id();
        $table->foreignId('service_id')->constrained()->onDelete('cascade');
        $table->foreignId('staff_profile_id')->constrained()->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('service_staff');
    Schema::dropIfExists('services');
}

    
   
};
