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
        Schema::table('produtos', function (Blueprint $table) {
            if (!Schema::hasColumn('produtos', 'categoria')) {
                $table->string('categoria')->nullable()->after('imagem');
            }

            if (!Schema::hasColumn('produtos', 'estoque')) {
                $table->unsignedInteger('estoque')->nullable()->after('categoria');
            }

            if (!Schema::hasColumn('produtos', 'status')) {
                $table->string('status')->default('ativo')->after('estoque');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            if (Schema::hasColumn('produtos', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('produtos', 'estoque')) {
                $table->dropColumn('estoque');
            }

            if (Schema::hasColumn('produtos', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }
};
