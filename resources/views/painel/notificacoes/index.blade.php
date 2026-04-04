@extends('layouts.painel')
@section('title', 'Notificações')
@section('breadcrumb', 'Notificações')

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center">
    <h1 class="page-title">Notificações</h1>
    @if($notificacoes->where('lida', false)->count() > 0)
    <form method="POST" action="{{ route('painel.notificacoes.marcar-todas') }}">
        @csrf
        <button type="submit" class="btn btn-ghost">Marcar todas como lidas</button>
    </form>
    @endif
</div>

<div class="card">
    @forelse($notificacoes as $n)
    <div style="display:flex; align-items:flex-start; gap:1rem; padding:1rem; border-bottom:1px solid var(--border); {{ !$n->lida ? 'background:rgba(27,74,82,0.15)' : '' }}">
        <div style="flex-shrink:0; margin-top:0.2rem">
            @php
                $icons = [
                    'tarefa'      => '📋',
                    'certidao'    => '📄',
                    'certificado' => '🔐',
                    'financeiro'  => '💰',
                    'demanda'     => '🎯',
                    'sistema'     => '⚙️',
                ];
                $icon = $icons[$n->tipo] ?? '🔔';
            @endphp
            <span style="font-size:1.25rem">{{ $icon }}</span>
        </div>
        <div style="flex:1; min-width:0">
            <div style="display:flex; justify-content:space-between; align-items:flex-start">
                <div style="font-weight:{{ $n->lida ? '400' : '600' }}; font-size:0.9rem">
                    {{ $n->titulo }}
                </div>
                <div style="font-size:0.75rem; color:var(--muted); white-space:nowrap; margin-left:1rem">
                    {{ $n->created_at->diffForHumans() }}
                </div>
            </div>
            @if($n->mensagem)
            <div style="font-size:0.85rem; color:var(--muted); margin-top:0.25rem">{{ $n->mensagem }}</div>
            @endif
        </div>
        @if(!$n->lida)
        <div style="flex-shrink:0">
            <form method="POST" action="{{ route('painel.notificacoes.marcar-lida', $n) }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm">✓ Lida</button>
            </form>
        </div>
        @endif
    </div>
    @empty
    <div style="text-align:center; padding:3rem; color:var(--muted)">
        <div style="font-size:2rem; margin-bottom:0.5rem">🔔</div>
        <div>Nenhuma notificação encontrada.</div>
    </div>
    @endforelse
</div>

@if($notificacoes->hasPages())
    <div style="padding:1rem">{{ $notificacoes->links() }}</div>
@endif
@endsection
