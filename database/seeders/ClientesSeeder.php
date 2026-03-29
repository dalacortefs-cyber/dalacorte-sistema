<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@dalacortefs.com.br')->first();

        $clientes = [
            [
                'nome'        => 'Empresa Exemplo LTDA',
                'email'       => 'contato@empresaexemplo.com.br',
                'cpf_cnpj'    => '12.345.678/0001-90',
                'tipo_pessoa' => 'juridica',
                'telefone'    => '(11) 3000-0000',
                'celular'     => '(11) 99000-0000',
                'cidade'      => 'São Paulo',
                'estado'      => 'SP',
                'status'      => 'ativo',
                'receita_mensal' => 15000.00,
            ],
            [
                'nome'        => 'João Silva',
                'email'       => 'joao.silva@email.com',
                'cpf_cnpj'    => '123.456.789-00',
                'tipo_pessoa' => 'fisica',
                'celular'     => '(11) 98000-0001',
                'cidade'      => 'São Paulo',
                'estado'      => 'SP',
                'status'      => 'ativo',
                'receita_mensal' => 5000.00,
            ],
            [
                'nome'        => 'Comércio ABC ME',
                'email'       => 'financeiro@comercioabc.com.br',
                'cpf_cnpj'    => '98.765.432/0001-10',
                'tipo_pessoa' => 'juridica',
                'telefone'    => '(21) 3100-0000',
                'cidade'      => 'Rio de Janeiro',
                'estado'      => 'RJ',
                'status'      => 'prospecto',
                'receita_mensal' => 8000.00,
            ],
        ];

        foreach ($clientes as $dados) {
            Cliente::updateOrCreate(
                ['cpf_cnpj' => $dados['cpf_cnpj']],
                array_merge($dados, ['user_id' => $admin?->id])
            );
        }

        $this->command->info('Clientes de exemplo criados.');
    }
}
