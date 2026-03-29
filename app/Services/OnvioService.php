<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OnvioService
{
    private Client $client;
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('ONVIO_BASE_URL', 'https://api.onvio.com.br');
        $this->apiKey  = env('ONVIO_API_KEY', '');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    public function buscarCliente(string $cpfCnpj): ?array
    {
        $cacheKey = "onvio_cliente_{$cpfCnpj}";

        return Cache::remember($cacheKey, 3600, function () use ($cpfCnpj) {
            try {
                $response = $this->client->get('/clientes', [
                    'query' => ['cpf_cnpj' => $cpfCnpj],
                ]);
                $data = json_decode($response->getBody()->getContents(), true);
                return $data['data'][0] ?? null;
            } catch (RequestException $e) {
                Log::warning('OnvioService: cliente não encontrado', ['cpf_cnpj' => $cpfCnpj]);
                return null;
            }
        });
    }

    public function listarClientes(array $filtros = []): array
    {
        try {
            $response = $this->client->get('/clientes', ['query' => $filtros]);
            return json_decode($response->getBody()->getContents(), true)['data'] ?? [];
        } catch (RequestException $e) {
            Log::error('OnvioService: erro ao listar clientes', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function buscarDocumentos(string $codigoCliente): array
    {
        try {
            $response = $this->client->get("/clientes/{$codigoCliente}/documentos");
            return json_decode($response->getBody()->getContents(), true)['data'] ?? [];
        } catch (RequestException $e) {
            Log::error('OnvioService: erro ao buscar documentos', ['codigo' => $codigoCliente]);
            return [];
        }
    }

    public function sincronizarCliente(array $dadosCliente): ?array
    {
        try {
            $response = $this->client->post('/clientes/sincronizar', ['json' => $dadosCliente]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('OnvioService: erro ao sincronizar cliente', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function buscarObrigacoesFiscais(string $codigoCliente, string $competencia): array
    {
        try {
            $response = $this->client->get("/clientes/{$codigoCliente}/obrigacoes", [
                'query' => ['competencia' => $competencia],
            ]);
            return json_decode($response->getBody()->getContents(), true)['data'] ?? [];
        } catch (RequestException $e) {
            Log::error('OnvioService: erro ao buscar obrigações', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function verificarConexao(): bool
    {
        try {
            $this->client->get('/ping');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
