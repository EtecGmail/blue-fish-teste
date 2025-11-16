<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('assunto');
            $table->text('mensagem');
            $table->string('status')->default('novo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contatos');
    }
};
