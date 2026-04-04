<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Novos campos para o painel
            $table->enum('role', ['admin', 'gestor', 'operacional', 'cliente'])->default('operacional')->after('email');
            $table->boolean('active')->default(true)->after('role');
            $table->foreignId('escritorio_id')->nullable()->after('active')->constrained('escritorios')->nullOnDelete();
            $table->foreignId('empresa_id')->nullable()->after('escritorio_id')->constrained('empresas')->nullOnDelete();
            $table->string('cargo')->nullable()->after('empresa_id');

            // Mantém compatibilidade: tipo já existe, apenas adiciona os novos
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['escritorio_id']);
            $table->dropForeign(['empresa_id']);
            $table->dropColumn(['role', 'active', 'escritorio_id', 'empresa_id', 'cargo']);
        });
    }
};
