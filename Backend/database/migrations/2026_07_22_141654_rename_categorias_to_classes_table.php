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
        // 1. Remover a chave estrangeira em especies temporariamente para permitir a alteração
        Schema::table('especies', function (Blueprint $table) {
            $table->dropForeign(['id_categoria']);
        });

        // 2. Renomear a tabela 'categorias' para 'classes' (ou 'classe')
        Schema::rename('categorias', 'classes');

        // 3. Renomear a coluna de chave primária dentro da tabela renomeada
        Schema::table('classes', function (Blueprint $table) {
            $table->renameColumn('id_categoria', 'id_classe');
        });

        // 4. Renomear a coluna e recriar a chave estrangeira na tabela 'especies'
        Schema::table('especies', function (Blueprint $table) {
            $table->renameColumn('id_categoria', 'id_classe');
            $table->foreign('id_classe')
                  ->references('id_classe')
                  ->on('classes')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('especies', function (Blueprint $table) {
            $table->dropForeign(['id_classe']);
        });

        Schema::table('classes', function (Blueprint $table) {
            $table->renameColumn('id_classe', 'id_categoria');
        });

        Schema::rename('classes', 'categorias');

        Schema::table('especies', function (Blueprint $table) {
            $table->renameColumn('id_classe', 'id_categoria');
            $table->foreign('id_categoria')
                  ->references('id_categoria')
                  ->on('categorias')
                  ->cascadeOnDelete();
        });
    }
};