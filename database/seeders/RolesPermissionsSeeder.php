<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissoes = [
            // Clientes
            'clientes.ver', 'clientes.criar', 'clientes.editar', 'clientes.excluir',
            // Extratos
            'extratos.ver', 'extratos.criar', 'extratos.excluir', 'extratos.analisar',
            // Tarefas
            'tarefas.ver', 'tarefas.criar', 'tarefas.editar', 'tarefas.excluir',
            // Leads
            'leads.ver', 'leads.criar', 'leads.editar', 'leads.excluir',
            // Notícias
            'noticias.ver', 'noticias.criar', 'noticias.editar', 'noticias.excluir', 'noticias.publicar',
            // RH
            'rh.ver', 'rh.gerenciar',
            // IA
            'ia.usar',
            // Dashboard
            'dashboard.ver',
            // Auditoria
            'auditoria.ver',
            // Usuários
            'usuarios.ver', 'usuarios.criar', 'usuarios.editar', 'usuarios.excluir',
        ];

        foreach ($permissoes as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
        }

        // ─── Roles ─────────────────────────────────────────────────────────

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $admin->syncPermissions(Permission::where('guard_name', 'api')->get());

        $funcionario = Role::firstOrCreate(['name' => 'funcionario', 'guard_name' => 'api']);
        $funcionario->syncPermissions([
            'clientes.ver', 'clientes.criar', 'clientes.editar',
            'extratos.ver', 'extratos.criar',
            'tarefas.ver', 'tarefas.criar', 'tarefas.editar',
            'leads.ver', 'leads.criar', 'leads.editar',
            'noticias.ver',
            'ia.usar',
            'dashboard.ver',
        ]);

        $cliente = Role::firstOrCreate(['name' => 'cliente', 'guard_name' => 'api']);
        $cliente->syncPermissions([
            'extratos.ver',
            'noticias.ver',
            'ia.usar',
        ]);

        $this->command->info('Roles e permissões criados com sucesso.');
    }
}
