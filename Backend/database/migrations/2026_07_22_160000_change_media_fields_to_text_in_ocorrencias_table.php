<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->text('foto_path')->nullable()->change();
            $table->text('video_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ocorrencias', function (Blueprint $table) {
            $table->string('foto_path')->nullable()->change();
            $table->string('video_path')->nullable()->change();
        });
    }
};
