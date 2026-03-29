<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('cpf_cnpj', 20)->unique();
            $table->enum('tipo_pessoa', ['fisica', 'juridica'])->default('fisica');
            $table->string('telefone', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->enum('status', ['ativo', 'inativo', 'prospecto'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->string('responsavel_onvio')->nullable();
            $table->string('codigo_onvio')->nullable();
            $table->decimal('receita_mensal', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('tipo_pessoa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
