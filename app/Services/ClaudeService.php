<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ClaudeService
{
    private Client $client;
    private string $apiKey;
    private string $model;
    private int $maxTokens;

    public function __construct()
    {
        $this->apiKey   = config('services.claude.api_key', env('CLAUDE_API_KEY'));
        $this->model    = config('services.claude.model', env('CLAUDE_MODEL', 'claude-sonnet-4-6'));
        $this->maxTokens = (int) config('services.claude.max_tokens', env('CLAUDE_MAX_TOKENS', 4096));

        $this->client = new Client([
            'base_uri' => 'https://api.anthropic.com',
            'headers'  => [
                'x-api-key'         => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ],
            'timeout' => 60,
        ]);
    }

    public function chat(string $mensagem, string $systemPrompt = '', array $historico = []): string
    {
        $messages = [];

        foreach ($historico as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        $messages[] = ['role' => 'user', 'content' => $mensagem];

        $payload = [
            'model'      => $this->model,
            'max_tokens' => $this->maxTokens,
            'messages'   => $messages,
        ];

        if ($systemPrompt) {
            $payload['system'] = $systemPrompt;
        }

        try {
            $response = $this->client->post('/v1/messages', ['json' => $payload]);
            $data     = json_decode($response->getBody()->getContents(), true);

            return $data['content'][0]['text'] ?? '';
        } catch (RequestException $e) {
            Log::error('ClaudeService error', ['message' => $e->getMessage()]);
            throw new \RuntimeException('Erro ao comunicar com Claude API: ' . $e->getMessage());
        }
    }

    public function analisarExtrato(array $transacoes, string $nomeCliente): string
    {
        $resumo = $this->formatarTransacoesParaIA($transacoes);

        $prompt = "Analise o extrato bancário do cliente {$nomeCliente} e forneça:\n"
            . "1. Resumo financeiro do período\n"
            . "2. Principais categorias de gastos\n"
            . "3. Tendências identificadas\n"
            . "4. Alertas ou pontos de atenção\n"
            . "5. Recomendações financeiras\n\n"
            . "Transações:\n{$resumo}";

        $system = "Você é um consultor financeiro especializado da Dalacorte Financial Solutions. "
            . "Analise os dados financeiros de forma objetiva, clara e em português brasileiro. "
            . "Seja específico nos valores e percentuais.";

        return $this->chat($prompt, $system);
    }

    public function responderPortalCliente(string $pergunta, array $dadosCliente): string
    {
        $system = "Você é o assistente virtual da Dalacorte Financial Solutions. "
            . "Responda dúvidas financeiras e contábeis de forma clara, amigável e em português brasileiro. "
            . "Dados do cliente: " . json_encode($dadosCliente, JSON_UNESCAPED_UNICODE);

        return $this->chat($pergunta, $system);
    }

    public function gerarResumoDashboard(array $dadosGerais): string
    {
        $dados  = json_encode($dadosGerais, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $prompt = "Com base nos dados gerenciais abaixo, gere um resumo executivo com insights e alertas:\n\n{$dados}";
        $system = "Você é um analista financeiro sênior da Dalacorte Financial Solutions. "
            . "Gere insights objetivos e acionáveis em português brasileiro.";

        return $this->chat($prompt, $system);
    }

    public function classificarLead(array $dadosLead): array
    {
        $dados  = json_encode($dadosLead, JSON_UNESCAPED_UNICODE);
        $prompt = "Classifique este lead e sugira a melhor abordagem comercial:\n{$dados}\n\n"
            . "Responda em JSON com: score (0-100), classificacao (frio/morno/quente), "
            . "servico_recomendado, proxima_acao, observacoes";

        $system = "Você é um especialista em vendas de serviços contábeis e financeiros.";

        $resposta = $this->chat($prompt, $system);

        $json = json_decode($resposta, true);
        return $json ?? ['score' => 50, 'classificacao' => 'morno', 'observacoes' => $resposta];
    }

    private function formatarTransacoesParaIA(array $transacoes): string
    {
        $linhas = [];
        foreach (array_slice($transacoes, 0, 100) as $t) {
            $linhas[] = sprintf(
                "%s | %s | R$ %s | %s",
                $t['data'] ?? '',
                $t['descricao'] ?? '',
                number_format((float)($t['valor'] ?? 0), 2, ',', '.'),
                $t['tipo'] ?? ''
            );
        }
        return implode("\n", $linhas);
    }
}
