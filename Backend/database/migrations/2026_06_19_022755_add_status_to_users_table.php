<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 🛡️ Garante a criação do tipo_conta padrão como Colaborador
            $table->string('tipo_conta')->default('Colaborador');
            
            // Cria o status Pendente
            $table->string('status')->default('Pendente'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo_conta', 'status']);
        });
    }
};