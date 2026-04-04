<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'checklist_items';

    protected $fillable = [
        'demanda_id', 'descricao', 'ordem', 'concluido', 'data_conclusao', 'usuario_conclusao',
    ];

    protected $casts = [
        'concluido'      => 'boolean',
        'data_conclusao' => 'date',
    ];

    public function demanda()
    {
        return $this->belongsTo(Demanda::class);
    }
}
