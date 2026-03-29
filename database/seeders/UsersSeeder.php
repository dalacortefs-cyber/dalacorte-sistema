<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrador Dalacorte',
                'email'    => 'admin@dalacortefs.com.br',
                'password' => Hash::make('Admin@2024'),
                'tipo'     => 'admin',
                'ativo'    => true,
                'role'     => 'admin',
            ],
            [
                'name'     => 'Funcionário Demo',
                'email'    => 'funcionario@dalacortefs.com.br',
                'password' => Hash::make('Func@2024'),
                'tipo'     => 'funcionario',
                'ativo'    => true,
                'role'     => 'funcionario',
            ],
            [
                'name'     => 'Cliente Demo',
                'email'    => 'cliente@dalacortefs.com.br',
                'password' => Hash::make('Cliente@2024'),
                'tipo'     => 'cliente',
                'ativo'    => true,
                'role'     => 'cliente',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->syncRoles([$role]);
        }

        $this->command->info('Usuários criados: admin, funcionario, cliente.');
    }
}
