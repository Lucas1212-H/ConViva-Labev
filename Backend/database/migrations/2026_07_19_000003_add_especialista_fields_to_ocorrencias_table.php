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
            $table->string('assigned_to')->nullable()->after('status');
            $table->string('habitat')->nullable()->after('video_path');
            $table->string('microhabitat')->nullable()->after('habitat');
            $table->string('classe')->nullable()->after('microhabitat');
            $table->string('ordem')->nullable()->after('classe');
            $table->string('familia')->nullable()->after('ordem');
            $table->string('especie')->nullable()->after('familia');
            $table->string('que_coletou')->nullable()->after('especie');
            $table->string('destino')->nullable()->after('que_coletou');
            $table->string('registrado_por')->nullable()->after('destino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->dropColumn(['assigned_to', 'habitat', 'microhabitat', 'classe', 'ordem', 'familia', 'especie', 'que_coletou', 'destino', 'registrado_por']);
        });
    }
};
