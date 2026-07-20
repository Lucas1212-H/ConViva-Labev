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
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->string('classe_etaria')->nullable();
            $table->string('tempo')->nullable();
            $table->string('campo_encontrado')->nullable();
            $table->text('observacoes')->nullable();
            $table->string('codigo_registro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->dropColumn([
                'classe_etaria',
                'tempo',
                'campo_encontrado',
                'observacoes',
                'codigo_registro'
            ]);
        });
    }
};
