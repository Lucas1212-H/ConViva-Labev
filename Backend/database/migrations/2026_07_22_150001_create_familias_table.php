<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id('id_familia');
            $table->string('nome_cientifico')->unique();
            $table->string('nome_popular');
            $table->foreignId('id_ordem')->constrained('ordens', 'id_ordem')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('familias');
    }
};
