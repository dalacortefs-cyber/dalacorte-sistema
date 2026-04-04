<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'escritorio_id', 'razao_social', 'nome_fantasia', 'cnpj',
        'inscricao_estadual', 'inscricao_municipal', 'cnae_principal', 'cnaes_secundarios',
        'regime_tributario', 'uf', 'municipio', 'tipo_atividade',
        'data_inicio_atividade', 'data_inicio_contrato',
        'valor_honorario_mensal', 'indice_reajuste', 'percentual_reajuste_fixo', 'mes_reajuste',
        'responsavel_interno', 'possui_empregados', 'qtd_empregados',
        'matriz_id', 'eh_matriz', 'status',
        'email', 'telefone', 'acesso_portal_cliente', 'email_portal',
        'score_cliente', 'complexidade_tributaria',
    ];

    protected $casts = [
        'data_inicio_atividade'  => 'date',
        'data_inicio_contrato'   => 'date',
        'possui_empregados'      => 'boolean',
        'eh_matriz'              => 'boolean',
        'acesso_portal_cliente'  => 'boolean',
        'valor_honorario_mensal' => 'decimal:2',
    ];

    public function escritorio()
    {
        return $this->belongsTo(Escritorio::class);
    }

    public function socios()
    {
        return $this->hasMany(Socio::class);
    }

    public function matriz()
    {
        return $this->belongsTo(Empresa::class, 'matriz_id');
    }

    public function filiais()
    {
        return $this->hasMany(Empresa::class, 'matriz_id');
    }

    public function tarefas()
    {
        return $this->hasMany(TarefaDfs::class);
    }

    public function contasReceber()
    {
        return $this->hasMany(ContaReceber::class);
    }

    public function contasReceberAtivas()
    {
        return $this->hasMany(ContaReceber::class)->whereIn('status', ['Pendente', 'Atrasado']);
    }

    public function certidoes()
    {
        return $this->hasMany(Certidao::class);
    }

    public function certificadosDigitais()
    {
        return $this->hasMany(CertificadoDigital::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function obrigacoesPersonalizadas()
    {
        return $this->hasMany(ObrigacaoPersonalizada::class);
    }

    public function contratoAtivo()
    {
        return $this->hasOne(Contrato::class)->where('ativo', true)->latest();
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', 'Ativa');
    }
}
