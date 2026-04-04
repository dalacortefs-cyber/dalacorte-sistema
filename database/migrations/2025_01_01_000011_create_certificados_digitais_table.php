<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificados_digitais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->enum('tipo', ['A1', 'A3', 'e-CPF', 'e-CNPJ']);
            $table->date('data_validade');
            $table->string('responsavel')->nullable();
            $table->string('arquivo_url')->nullable();
            $table->enum('status', ['Válido', 'A Vencer', 'Vencido'])->default('Válido');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados_digitais');
    }
};
