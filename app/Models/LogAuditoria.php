<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAuditoria extends Model
{
    public $timestamps = false;

    protected $table = 'logs_auditoria';

    protected $fillable = [
        'user_id', 'acao', 'modulo', 'modelo_tipo', 'modelo_id',
        'dados_antes', 'dados_depois', 'ip', 'user_agent', 'observacao',
    ];

    protected $casts = [
        'dados_antes'  => 'array',
        'dados_depois' => 'array',
        'created_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modelo()
    {
        return $this->morphTo('modelo');
    }
}
