<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rank_histories', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel employees
            $table->foreignId('employee_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Data pangkat
            $table->string('rank');
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rank_histories');
    }
};