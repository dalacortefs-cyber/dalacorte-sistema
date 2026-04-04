<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scores_colaboradores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escritorio_id')->constrained('escritorios')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('usuario_nome');
            $table->string('mes_referencia', 7); // MM/YYYY
            $table->decimal('score', 5, 2)->default(0);
            $table->integer('total_tarefas')->default(0);
            $table->integer('tarefas_concluidas')->default(0);
            $table->integer('tarefas_no_prazo')->default(0);
            $table->integer('tarefas_atrasadas')->default(0);
            $table->decimal('taxa_retrabalho', 5, 2)->default(0);
            $table->decimal('sla_medio_dias', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['escritorio_id', 'user_id', 'mes_referencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores_colaboradores');
    }
};
