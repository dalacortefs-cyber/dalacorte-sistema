<?php

namespace Database\Seeders;

use App\Models\Escritorio;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Criar escritório padrão se não existir
        $escritorio = Escritorio::firstOrCreate(
            ['cnpj' => ''],
            [
                'nome_escritorio' => 'Dalacorte Financial Solutions',
                'email'           => 'dalacortefs@gmail.com',
                'cor_primaria'    => '#1B4A52',
                'cor_secundaria'  => '#8B6914',
                'cor_destaque'    => '#245266',
            ]
        );

        // Criar usuário admin
        User::updateOrCreate(
            ['email' => 'dalacortefs@gmail.com'],
            [
                'name'         => 'Matheus Dalacorte',
                'password'     => Hash::make('DFS@Admin2024'),
                'role'         => 'admin',
                'active'       => true,
                'tipo'         => 'admin',
                'ativo'        => true,
                'escritorio_id'=> $escritorio->id,
            ]
        );

        $this->command->info('✓ Admin criado: dalacortefs@gmail.com / DFS@Admin2024');
        $this->command->info("✓ Escritório ID: {$escritorio->id}");
    }
}
