<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certidoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->enum('tipo', ['CND Federal', 'CND Estadual', 'CND Municipal', 'FGTS', 'Trabalhista', 'Outra']);
            $table->date('data_emissao')->nullable();
            $table->date('data_validade');
            $table->enum('status', ['Válida', 'Vencida', 'A Vencer'])->default('Válida');
            $table->string('arquivo_url')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certidoes');
    }
};
