<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->unsignedInteger('quantidade');
            $table->decimal('valor_total', 10, 2);
            $table->string('status')->default('concluida');
            $table->timestamps();

            $table->index(['user_id', 'produto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
