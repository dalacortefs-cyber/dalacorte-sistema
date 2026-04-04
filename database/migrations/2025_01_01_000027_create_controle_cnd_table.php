<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('controle_cnd', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->enum('esfera', ['Federal', 'Estadual', 'Municipal']);
            $table->date('data_consulta');
            $table->enum('status', ['Positiva', 'Negativa', 'Erro']);
            $table->date('data_validade')->nullable();
            $table->string('arquivo_url')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controle_cnd');
    }
};
