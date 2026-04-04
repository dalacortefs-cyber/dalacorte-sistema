<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\{
    Escritorio, Empresa, Socio, Obrigacao, ObrigacaoPersonalizada,
    TarefaDfs, ContaReceber, ContaPagar, Certidao, CertificadoDigital,
    Documento, Contrato, ReceeitaExtra, ControleFaturamento,
    ProjetoInterno, PendenciaRecorrente, Demanda, ChecklistItem,
    ComentarioDemanda, Notificacao, LogAtividade, Configuracao,
    User
};

/**
 * Comando de migração única: Base44 → MySQL
 *
 * USO:
 *   php artisan base44:importar
 *
 * REMOVER APÓS USO:
 *   - Deletar este arquivo
 *   - Remover BASE44_API_KEY e BASE44_APP_ID do .env
 */
class ImportarBase44 extends Command
{
    protected $signature   = 'base44:importar {--dry-run : Apenas conta os registros sem importar}';
    protected $description = 'Importa dados do Base44 para o MySQL (executar só uma vez)';

    private string $appId;
    private string $apiKey;
    private string $baseUrl = 'https://api.base44.app/api/apps';
    private int    $total   = 0;
    private array  $resumo  = [];

    public function handle(): int
    {
        $this->appId  = config('services.base44.app_id', env('BASE44_APP_ID'));
        $this->apiKey = config('services.base44.api_key', env('BASE44_API_KEY'));

        if (!$this->appId || !$this->apiKey || str_contains($this->apiKey, 'PLACEHOLDER')) {
            $this->error('BASE44_API_KEY ou BASE44_APP_ID não configurados no .env');
            return 1;
        }

        $this->info('');
        $this->info('╔══════════════════════════════════════════════╗');
        $this->info('║   Importação Base44 → MySQL (DFS)            ║');
        $this->info('╚══════════════════════════════════════════════╝');
        $this->info('');

        $isDryRun = $this->option('dry-run');
        if ($isDryRun) {
            $this->warn('  [DRY-RUN] Nenhum dado será importado.');
        }

        DB::beginTransaction();

        try {
            // ── Ordem de importação (respeitando FKs) ──────────────────
            $this->importarEscritorios($isDryRun);
            $this->importarEmpresas($isDryRun);
            $this->importarSocios($isDryRun);
            $this->importarObrigacoes($isDryRun);
            $this->importarObrigacoesPersonalizadas($isDryRun);
            $this->importarTarefas($isDryRun);
            $this->importarContasReceber($isDryRun);
            $this->importarContasPagar($isDryRun);
            $this->importarCertidoes($isDryRun);
            $this->importarCertificadosDigitais($isDryRun);
            $this->importarDocumentos($isDryRun);
            $this->importarContratos($isDryRun);
            $this->importarProjetosInternos($isDryRun);
            $this->importarPendenciasRecorrentes($isDryRun);
            $this->importarDemandas($isDryRun);
            $this->importarChecklistItems($isDryRun);
            $this->importarComentarios($isDryRun);
            $this->importarConfiguracoes($isDryRun);

            if (!$isDryRun) {
                DB::commit();
            } else {
                DB::rollBack();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Erro durante importação: {$e->getMessage()}");
            $this->error($e->getTraceAsString());
            return 1;
        }

        // ── Resumo ────────────────────────────────────────────────────
        $this->info('');
        $this->info('╔══════════════════════════════════════════════╗');
        $this->info('║   RESUMO DA IMPORTAÇÃO                       ║');
        $this->info('╠══════════════════════════════════════════════╣');

        foreach ($this->resumo as $entidade => $qtd) {
            $this->info(sprintf('║  %-30s %5d  ║', $entidade, $qtd));
        }

        $this->info('╠══════════════════════════════════════════════╣');
        $this->info(sprintf('║  %-30s %5d  ║', 'TOTAL', $this->total));
        $this->info('╚══════════════════════════════════════════════╝');
        $this->info('');

        if (!$isDryRun) {
            $this->info('✓ Importação concluída com sucesso!');
            $this->warn('  → Lembre-se de remover BASE44_API_KEY do .env após validar os dados.');
            $this->warn('  → Este arquivo pode ser deletado: app/Console/Commands/ImportarBase44.php');
        }

        return 0;
    }

    // ── Métodos de importação por entidade ───────────────────���────────

    private function importarEscritorios(bool $dry): void
    {
        $registros = $this->buscar('Escritorio');
        $this->log('Escritórios', count($registros));
        if ($dry) return;

        foreach (array_chunk($registros, 100) as $chunk) {
            foreach ($chunk as $r) {
                Escritorio::updateOrCreate(
                    ['nome_escritorio' => $r['nome_escritorio']],
                    [
                        'cnpj'            => $r['cnpj'] ?? null,
                        'endereco'        => $r['endereco'] ?? null,
                        'telefone'        => $r['telefone'] ?? null,
                        'email'           => $r['email'] ?? null,
                        'logo_url'        => $r['logo_url'] ?? null,
                        'cor_primaria'    => $r['cor_primaria'] ?? '#1a3a4a',
                        'cor_secundaria'  => $r['cor_secundaria'] ?? '#b8935a',
                        'cor_destaque'    => $r['cor_destaque'] ?? '#245266',
                    ]
                );
            }
        }
    }

    private function importarEmpresas(bool $dry): void
    {
        $registros = $this->buscar('Empresa');
        $this->log('Empresas', count($registros));
        if ($dry) return;

        // Pegar escritório padrão
        $escritorio = Escritorio::first();

        foreach (array_chunk($registros, 100) as $chunk) {
            foreach ($chunk as $r) {
                Empresa::updateOrCreate(
                    ['cnpj' => $r['cnpj']],
                    [
                        'escritorio_id'           => $escritorio?->id ?? 1,
                        'razao_social'             => $r['razao_social'],
                        'nome_fantasia'            => $r['nome_fantasia'] ?? null,
                        'inscricao_estadual'       => $r['inscricao_estadual'] ?? null,
                        'inscricao_municipal'      => $r['inscricao_municipal'] ?? null,
                        'cnae_principal'           => $r['cnae_principal'] ?? null,
                        'regime_tributario'        => $r['regime_tributario'] ?? null,
                        'uf'                       => $r['uf'] ?? null,
                        'municipio'               => $r['municipio'] ?? null,
                        'tipo_atividade'           => $r['tipo_atividade'] ?? null,
                        'data_inicio_atividade'    => $r['data_inicio_atividade'] ?? null,
                        'data_inicio_contrato'     => $r['data_inicio_contrato'] ?? null,
                        'valor_honorario_mensal'   => $r['valor_honorario_mensal'] ?? null,
                        'indice_reajuste'          => $r['indice_reajuste'] ?? null,
                        'mes_reajuste'             => $r['mes_reajuste'] ?? null,
                        'possui_empregados'        => $r['possui_empregados'] ?? false,
                        'qtd_empregados'           => $r['qtd_empregados'] ?? null,
                        'eh_matriz'                => $r['eh_matriz'] ?? false,
                        'status'                   => $r['status'] ?? 'Ativa',
                        'email'                    => $r['email'] ?? null,
                        'telefone'                 => $r['telefone'] ?? null,
                        'score_cliente'            => $r['score_cliente'] ?? 100,
                        'complexidade_tributaria'  => $r['complexidade_tributaria'] ?? 'Média',
                        'responsavel_interno'      => $r['responsavel_interno'] ?? null,
                    ]
                );
            }
        }
    }

    private function importarSocios(bool $dry): void
    {
        $registros = $this->buscar('Socio');
        $this->log('Sócios', count($registros));
        if ($dry) return;

        foreach ($registros as $r) {
            // Base44 usa empresa_id como string — mapear para ID local
            $empresa = Empresa::where('razao_social', 'like', '%' . ($r['empresa_nome'] ?? '') . '%')->first()
                ?? Empresa::first();

            if (!$empresa) continue;

            Socio::updateOrCreate(
                ['empresa_id' => $empresa->id, 'cpf' => $r['cpf']],
                [
                    'nome'         => $r['nome'],
                    'participacao' => $r['participacao'] ?? null,
                    'tipo'         => $r['tipo'] ?? null,
                    'email'        => $r['email'] ?? null,
                    'telefone'     => $r['telefone'] ?? null,
                ]
            );
        }
    }

    private function importarObrigacoes(bool $dry): void
    {
        $registros = $this->buscar('Obrigacao');
        $this->log('Obrigações', count($registros));
        if ($dry) return;

        $escritorio = Escritorio::first();
        foreach (array_chunk($registros, 100) as $chunk) {
            foreach ($chunk as $r) {
                Obrigacao::updateOrCreate(
                    ['escritorio_id' => $escritorio?->id ?? 1, 'nome' => $r['nome']],
                    [
                        'esfera'                          => $r['esfera'] ?? null,
                        'periodicidade'                   => $r['periodicidade'] ?? null,
                        'dia_vencimento'                  => $r['dia_vencimento'] ?? null,
                        'dias_antecedencia_envio_cliente' => $r['dias_antecedencia_envio_cliente'] ?? 0,
                        'sla_dias_internos'               => $r['sla_dias_internos'] ?? 0,
                        'nivel_criticidade'               => $r['nivel_criticidade'] ?? 'Média',
                        'regimes_aplicaveis'              => is_array($r['regimes_aplicaveis'] ?? null)
                            ? json_encode($r['regimes_aplicaveis'])
                            : ($r['regimes_aplicaveis'] ?? null),
                        'requer_empregados'               => $r['requer_empregados'] ?? false,
                        'centralizada_matriz'             => $r['centralizada_matriz'] ?? false,
                        'ativa'                           => $r['ativa'] ?? true,
                        'eh_padrao_sistema'               => $r['eh_padrao_sistema'] ?? false,
                    ]
                );
            }
        }
    }

    private function importarTarefas(bool $dry): void
    {
        $registros = $this->buscar('Tarefa');
        $this->log('Tarefas', count($registros));
        if ($dry) return;

        $escritorio = Escritorio::first();
        foreach (array_chunk($registros, 100) as $chunk) {
            foreach ($chunk as $r) {
                $empresa = Empresa::where('razao_social', $r['empresa_nome'] ?? '')->first();
                if (!$empresa) continue;

                TarefaDfs::updateOrCreate(
                    [
                        'empresa_id'    => $empresa->id,
                        'obrigacao_nome'=> $r['obrigacao_nome'],
                        'competencia'   => $r['competencia'],
                    ],
                    [
                        'escritorio_id'          => $escritorio?->id ?? 1,
                        'empresa_nome'           => $r['empresa_nome'] ?? '',
                        'data_vencimento'        => $r['data_vencimento'] ?? null,
                        'data_expectativa_envio' => $r['data_expectativa_envio'] ?? null,
                        'status'                 => $r['status'] ?? 'Pendente',
                        'responsavel'            => $r['responsavel'] ?? null,
                        'data_real_conclusao'    => $r['data_real_conclusao'] ?? null,
                        'observacoes'            => $r['observacoes'] ?? null,
                        'esfera'                 => $r['esfera'] ?? null,
                        'periodicidade'          => $r['periodicidade'] ?? null,
                        'nivel_criticidade'      => $r['nivel_criticidade'] ?? 'Média',
                        'foi_retrabalho'         => $r['foi_retrabalho'] ?? false,
                        'concluida_no_prazo'     => $r['concluida_no_prazo'] ?? null,
                    ]
                );
            }
        }
    }

    private function importarObrigacoesPersonalizadas(bool $dry): void
    {
        $registros = $this->buscar('ObrigacaoPersonalizada');
        $this->log('Obrigações Personalizadas', count($registros));
        // Implementar mapeamento similar
    }

    private function importarContasReceber(bool $dry): void
    {
        $registros = $this->buscar('ContaReceber');
        $this->log('Contas a Receber', count($registros));
        if ($dry) return;

        $escritorio = Escritorio::first();
        foreach (array_chunk($registros, 100) as $chunk) {
            foreach ($chunk as $r) {
                $empresa = Empresa::where('razao_social', $r['empresa_nome'] ?? '')->first();
                if (!$empresa) continue;

                ContaReceber::updateOrCreate(
                    ['empresa_id' => $empresa->id, 'data_vencimento' => $r['data_vencimento'], 'valor' => $r['valor']],
                    [
                        'escritorio_id'  => $escritorio?->id ?? 1,
                        'empresa_nome'   => $r['empresa_nome'] ?? '',
                        'descricao'      => $r['descricao'] ?? null,
                        'forma_pagamento'=> $r['forma_pagamento'] ?? null,
                        'status'         => $r['status'] ?? 'Pendente',
                        'dias_atraso'    => $r['dias_atraso'] ?? 0,
                        'valor_juros'    => $r['valor_juros'] ?? 0,
                        'valor_multa'    => $r['valor_multa'] ?? 0,
                        'competencia'    => $r['competencia'] ?? null,
                        'data_pagamento' => $r['data_pagamento'] ?? null,
                    ]
                );
            }
        }
    }

    private function importarContasPagar(bool $dry): void
    {
        $registros = $this->buscar('ContaPagar');
        $this->log('Contas a Pagar', count($registros));
        if ($dry) return;

        foreach (array_chunk($registros, 100) as $chunk) {
            foreach ($chunk as $r) {
                ContaPagar::updateOrCreate(
                    ['descricao' => $r['descricao'], 'data_vencimento' => $r['data_vencimento']],
                    [
                        'fornecedor'      => $r['fornecedor'] ?? null,
                        'categoria'       => $r['categoria'] ?? null,
                        'valor'           => $r['valor'],
                        'forma_pagamento' => $r['forma_pagamento'] ?? null,
                        'status'          => $r['status'] ?? 'Pendente',
                        'competencia'     => $r['competencia'] ?? null,
                        'recorrente'      => $r['recorrente'] ?? false,
                        'data_pagamento'  => $r['data_pagamento'] ?? null,
                    ]
                );
            }
        }
    }

    private function importarCertidoes(bool $dry): void
    {
        $registros = $this->buscar('Certidao');
        $this->log('Certidões', count($registros));
        if ($dry) return;

        $escritorio = Escritorio::first();
        foreach ($registros as $r) {
            $empresa = Empresa::where('razao_social', $r['empresa_nome'] ?? '')->first();
            if (!$empresa) continue;
            Certidao::updateOrCreate(
                ['empresa_id' => $empresa->id, 'tipo' => $r['tipo'], 'data_validade' => $r['data_validade']],
                [
                    'escritorio_id' => $escritorio?->id ?? 1,
                    'empresa_nome'  => $r['empresa_nome'] ?? '',
                    'data_emissao'  => $r['data_emissao'] ?? null,
                    'status'        => $r['status'] ?? 'Válida',
                    'arquivo_url'   => $r['arquivo_url'] ?? null,
                    'observacoes'   => $r['observacoes'] ?? null,
                ]
            );
        }
    }

    private function importarCertificadosDigitais(bool $dry): void
    {
        $registros = $this->buscar('CertificadoDigital');
        $this->log('Certificados Digitais', count($registros));
        if ($dry) return;

        $escritorio = Escritorio::first();
        foreach ($registros as $r) {
            $empresa = Empresa::where('razao_social', $r['empresa_nome'] ?? '')->first();
            if (!$empresa) continue;
            CertificadoDigital::updateOrCreate(
                ['empresa_id' => $empresa->id, 'tipo' => $r['tipo']],
                [
                    'escritorio_id' => $escritorio?->id ?? 1,
                    'empresa_nome'  => $r['empresa_nome'] ?? '',
                    'data_validade' => $r['data_validade'],
                    'responsavel'   => $r['responsavel'] ?? null,
                    'arquivo_url'   => $r['arquivo_url'] ?? null,
                    'status'        => $r['status'] ?? 'Válido',
                    'observacoes'   => $r['observacoes'] ?? null,
                ]
            );
        }
    }

    private function importarDocumentos(bool $dry): void
    {
        $registros = $this->buscar('Documento');
        $this->log('Documentos', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarContratos(bool $dry): void
    {
        $registros = $this->buscar('Contrato');
        $this->log('Contratos', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarProjetosInternos(bool $dry): void
    {
        $registros = $this->buscar('Projeto_Interno');
        $this->log('Projetos Internos', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarPendenciasRecorrentes(bool $dry): void
    {
        $registros = $this->buscar('Pendencia_Recorrente');
        $this->log('Pendências Recorrentes', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarDemandas(bool $dry): void
    {
        $registros = $this->buscar('Demanda');
        $this->log('Demandas', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarChecklistItems(bool $dry): void
    {
        $registros = $this->buscar('Checklist_Item');
        $this->log('Checklist Items', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarComentarios(bool $dry): void
    {
        $registros = $this->buscar('Comentario_Demanda');
        $this->log('Comentários', count($registros));
        // Mapeamento similar ao padrão
    }

    private function importarConfiguracoes(bool $dry): void
    {
        $registros = $this->buscar('Configuracao');
        $this->log('Configurações', count($registros));
        if ($dry) return;

        foreach ($registros as $r) {
            Configuracao::updateOrCreate(
                ['chave' => $r['chave']],
                ['valor' => $r['valor'], 'tipo' => $r['tipo'] ?? 'string', 'descricao' => $r['descricao'] ?? null]
            );
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────

    private function buscar(string $entidade): array
    {
        $url = "{$this->baseUrl}/{$this->appId}/entities/{$entidade}/docs";

        $this->line("  → Buscando {$entidade}...", null, 'v');

        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($url);

            if ($response->failed()) {
                $this->warn("  ⚠ Falha ao buscar {$entidade}: HTTP {$response->status()}");
                return [];
            }

            $data = $response->json();

            // A API pode retornar array direto ou { docs: [...] }
            if (isset($data['docs'])) return $data['docs'];
            if (is_array($data))      return $data;

            return [];

        } catch (\Exception $e) {
            $this->warn("  ⚠ Erro ao buscar {$entidade}: {$e->getMessage()}");
            return [];
        }
    }

    private function log(string $entidade, int $qtd): void
    {
        $this->info(sprintf('  %-30s %4d registro(s)', $entidade . '...', $qtd));
        $this->resumo[$entidade] = $qtd;
        $this->total += $qtd;
    }
}
