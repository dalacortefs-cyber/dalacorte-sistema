<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('fornecedor')->nullable();
            $table->enum('categoria', ['Aluguel', 'Salários', 'Software', 'Material', 'Impostos', 'Marketing', 'Telefone/Internet', 'Outros'])->nullable();
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('forma_pagamento', ['PIX', 'Boleto', 'Transferência', 'Cartão', 'Dinheiro'])->nullable();
            $table->enum('status', ['Pendente', 'Pago', 'Atrasado', 'Cancelado'])->default('Pendente');
            $table->string('competencia', 7)->nullable();
            $table->boolean('recorrente')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_pagar');
    }
};
