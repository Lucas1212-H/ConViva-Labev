<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('especies', function (Blueprint $table) {
            $table->string('ordem')->nullable()->after('nome_popular');
            $table->string('familia')->nullable()->after('ordem');
        });
    }

    public function down(): void
    {
        Schema::table('especies', function (Blueprint $table) {
            $table->dropColumn(['ordem', 'familia']);
        });
    }
};
