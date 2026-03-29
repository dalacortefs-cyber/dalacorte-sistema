<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class ExtratoParserService
{
    public function processar(UploadedFile $arquivo, string $tipo): array
    {
        return match ($tipo) {
            'csv'  => $this->processarCsv($arquivo),
            'ofx'  => $this->processarOfx($arquivo),
            'xlsx' => $this->processarXlsx($arquivo),
            default => throw new \InvalidArgumentException("Tipo de arquivo não suportado: {$tipo}"),
        };
    }

    private function processarCsv(UploadedFile $arquivo): array
    {
        $csv = Reader::createFromPath($arquivo->getRealPath(), 'r');
        $csv->setHeaderOffset(0);

        $transacoes = [];
        $totalEntradas = 0.0;
        $totalSaidas   = 0.0;

        foreach ($csv->getRecords() as $registro) {
            $valor = $this->normalizarValor($registro['valor'] ?? $registro['Valor'] ?? '0');
            $tipo  = $valor >= 0 ? 'credito' : 'debito';

            if ($valor >= 0) {
                $totalEntradas += $valor;
            } else {
                $totalSaidas += abs($valor);
            }

            $transacoes[] = [
                'data'      => $this->normalizarData($registro['data'] ?? $registro['Data'] ?? ''),
                'descricao' => trim($registro['descricao'] ?? $registro['Descricao'] ?? $registro['Histórico'] ?? ''),
                'valor'     => $valor,
                'tipo'      => $tipo,
                'categoria' => $this->classificarTransacao($registro['descricao'] ?? ''),
            ];
        }

        return [
            'transacoes'     => $transacoes,
            'total_entradas' => $totalEntradas,
            'total_saidas'   => $totalSaidas,
            'total'          => count($transacoes),
        ];
    }

    private function processarOfx(UploadedFile $arquivo): array
    {
        $conteudo   = file_get_contents($arquivo->getRealPath());
        $transacoes = [];
        $totalEntradas = 0.0;
        $totalSaidas   = 0.0;

        preg_match_all('/<STMTTRN>(.*?)<\/STMTTRN>/s', $conteudo, $matches);

        foreach ($matches[1] as $bloco) {
            $tipo     = $this->extrairTag($bloco, 'TRNTYPE');
            $data     = $this->extrairTag($bloco, 'DTPOSTED');
            $valor    = (float) $this->extrairTag($bloco, 'TRNAMT');
            $descricao = $this->extrairTag($bloco, 'MEMO') ?: $this->extrairTag($bloco, 'NAME');

            if ($valor >= 0) {
                $totalEntradas += $valor;
            } else {
                $totalSaidas += abs($valor);
            }

            $transacoes[] = [
                'data'      => $this->normalizarDataOfx($data),
                'descricao' => $descricao,
                'valor'     => $valor,
                'tipo'      => $valor >= 0 ? 'credito' : 'debito',
                'categoria' => $this->classificarTransacao($descricao),
            ];
        }

        return [
            'transacoes'     => $transacoes,
            'total_entradas' => $totalEntradas,
            'total_saidas'   => $totalSaidas,
            'total'          => count($transacoes),
        ];
    }

    private function processarXlsx(UploadedFile $arquivo): array
    {
        // Usa maatwebsite/excel via facade
        $rows = \Maatwebsite\Excel\Facades\Excel::toArray([], $arquivo);
        $data = $rows[0] ?? [];
        $header = array_shift($data);

        $transacoes    = [];
        $totalEntradas = 0.0;
        $totalSaidas   = 0.0;

        foreach ($data as $row) {
            $linha = array_combine($header, $row);
            $valor = $this->normalizarValor($linha['valor'] ?? $linha['Valor'] ?? '0');
            $tipo  = $valor >= 0 ? 'credito' : 'debito';

            if ($valor >= 0) {
                $totalEntradas += $valor;
            } else {
                $totalSaidas += abs($valor);
            }

            $transacoes[] = [
                'data'      => $this->normalizarData($linha['data'] ?? $linha['Data'] ?? ''),
                'descricao' => $linha['descricao'] ?? $linha['Descricao'] ?? '',
                'valor'     => $valor,
                'tipo'      => $tipo,
                'categoria' => $this->classificarTransacao($linha['descricao'] ?? ''),
            ];
        }

        return [
            'transacoes'     => $transacoes,
            'total_entradas' => $totalEntradas,
            'total_saidas'   => $totalSaidas,
            'total'          => count($transacoes),
        ];
    }

    private function classificarTransacao(string $descricao): string
    {
        $desc = strtolower($descricao);

        $categorias = [
            'salario'      => ['salario', 'salário', 'folha', 'remuneracao'],
            'fornecedor'   => ['fornecedor', 'nf ', 'nota fiscal', 'pagto'],
            'imposto'      => ['das', 'darf', 'gps', 'iss', 'icms', 'inss', 'fgts', 'irrf'],
            'aluguel'      => ['aluguel', 'locacao', 'locação', 'imovel'],
            'servicos'     => ['internet', 'telefone', 'energia', 'agua', 'luz'],
            'transferencia'=> ['ted', 'doc', 'pix', 'transf'],
            'tarifa'       => ['tarifa', 'taxa', 'manutencao', 'manutenção', 'anuidade'],
            'vendas'       => ['venda', 'recebimento', 'deposito', 'depósito'],
        ];

        foreach ($categorias as $categoria => $palavras) {
            foreach ($palavras as $palavra) {
                if (str_contains($desc, $palavra)) {
                    return $categoria;
                }
            }
        }

        return 'outros';
    }

    private function normalizarValor(string $valor): float
    {
        $valor = str_replace(['R$', ' ', '.'], '', $valor);
        $valor = str_replace(',', '.', $valor);
        return (float) $valor;
    }

    private function normalizarData(string $data): string
    {
        if (!$data) return '';
        try {
            return \Carbon\Carbon::parse($data)->format('Y-m-d');
        } catch (\Exception $e) {
            return $data;
        }
    }

    private function normalizarDataOfx(string $data): string
    {
        if (strlen($data) >= 8) {
            return substr($data, 0, 4) . '-' . substr($data, 4, 2) . '-' . substr($data, 6, 2);
        }
        return $data;
    }

    private function extrairTag(string $texto, string $tag): string
    {
        preg_match("/<{$tag}>(.*?)(?:<\/|<|\n)/s", $texto, $match);
        return trim($match[1] ?? '');
    }
}
