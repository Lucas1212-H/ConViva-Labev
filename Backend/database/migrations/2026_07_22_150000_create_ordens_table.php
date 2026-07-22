<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id('id_ordem');
            $table->string('nome_cientifico')->unique();
            $table->string('nome_popular');
            $table->foreignId('id_classe')->constrained('classes', 'id_classe')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
