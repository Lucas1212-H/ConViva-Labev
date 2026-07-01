<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->boolean('publicado_no_mapa')->default(false)->after('status');
        });

        DB::table('ocorrencias')
            ->where('status', 'Publicado')
            ->update(['publicado_no_mapa' => true]);
    }

    public function down(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->dropColumn('publicado_no_mapa');
        });
    }
};