<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contas_receber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('empresa_nome');
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('forma_pagamento', ['PIX', 'Boleto', 'Transferência', 'Cartão', 'Dinheiro'])->nullable();
            $table->enum('status', ['Pendente', 'Pago', 'Atrasado', 'Cancelado'])->default('Pendente');
            $table->integer('dias_atraso')->default(0);
            $table->decimal('valor_juros', 10, 2)->default(0);
            $table->decimal('valor_multa', 10, 2)->default(0);
            $table->string('competencia', 7)->nullable();
            $table->enum('tipo_origem', ['Honorário Mensal', 'Serviço Extra', 'IRPF', 'Abertura de Empresa', 'Encerramento', 'Consultoria', 'Outros'])->default('Honorário Mensal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_receber');
    }
};
