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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('staff_profile_id')->constrained()->onDelete('cascade');
        $table->tinyInteger('day_of_week'); // 0 = Domingo, 1 = Lunes, ..., 6 = Sábado
        $table->time('start_time');         // Ej. 08:00:00
        $table->time('end_time');           // Ej. 17:00:00
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
