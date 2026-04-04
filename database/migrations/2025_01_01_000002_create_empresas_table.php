<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj', 18)->unique();
            $table->string('inscricao_estadual')->nullable();
            $table->string('inscricao_municipal')->nullable();
            $table->string('cnae_principal')->nullable();
            $table->string('cnaes_secundarios')->nullable();
            $table->enum('regime_tributario', ['MEI', 'Simples Nacional', 'Lucro Presumido', 'Lucro Real'])->nullable();
            $table->char('uf', 2)->nullable();
            $table->string('municipio')->nullable();
            $table->enum('tipo_atividade', ['Comércio', 'Serviço', 'Indústria', 'Misto'])->nullable();
            $table->date('data_inicio_atividade')->nullable();
            $table->date('data_inicio_contrato')->nullable();
            $table->decimal('valor_honorario_mensal', 10, 2)->nullable();
            $table->enum('indice_reajuste', ['IPCA', 'IGP-M', 'Fixo'])->nullable();
            $table->decimal('percentual_reajuste_fixo', 5, 2)->nullable();
            $table->tinyInteger('mes_reajuste')->nullable();
            $table->string('responsavel_interno')->nullable();
            $table->boolean('possui_empregados')->default(false);
            $table->integer('qtd_empregados')->nullable();
            $table->foreignId('matriz_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->boolean('eh_matriz')->default(false);
            $table->enum('status', ['Ativa', 'Inativa', 'Suspensa'])->default('Ativa');
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->boolean('acesso_portal_cliente')->default(false);
            $table->string('email_portal')->nullable();
            $table->integer('score_cliente')->default(100);
            $table->enum('complexidade_tributaria', ['Baixa', 'Média', 'Alta'])->default('Média');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
