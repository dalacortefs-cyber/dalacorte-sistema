<?php

use App\Http\Controllers\Painel\AuthPainelController;
use App\Http\Controllers\Painel\DashboardController;
use App\Http\Controllers\Painel\EmpresaController;
use App\Http\Controllers\Painel\TarefaController;
use App\Http\Controllers\Painel\FinanceiroController;
use App\Http\Controllers\Painel\CertidaoController;
use App\Http\Controllers\Painel\CertificadoDigitalController;
use App\Http\Controllers\Painel\DocumentoController;
use App\Http\Controllers\Painel\NotificacaoController;
use App\Http\Controllers\Painel\DemandaController;
use App\Http\Controllers\Painel\ProjetoInternoController;
use App\Http\Controllers\Painel\IndicadorController;
use App\Http\Controllers\Painel\UsuarioPainelController;
use App\Http\Controllers\Painel\ObrigacaoController;
use App\Http\Controllers\Painel\ConfiguracaoController;
use Illuminate\Support\Facades\Route;

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthPainelController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthPainelController::class, 'login'])->name('login.post')
        ->middleware('throttle:5,1');
});

Route::post('/logout', [AuthPainelController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Painel (autenticado) ─────────────────────────────────────────────────────
Route::prefix('painel')->name('painel.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Empresas
    Route::resource('empresas', EmpresaController::class);
    Route::get('empresas/{empresa}/socios', [EmpresaController::class, 'socios'])->name('empresas.socios');
    Route::post('empresas/{empresa}/socios', [EmpresaController::class, 'storeSocio'])->name('empresas.socios.store');
    Route::delete('empresas/{empresa}/socios/{socio}', [EmpresaController::class, 'destroySocio'])->name('empresas.socios.destroy');

    // Obrigações
    Route::resource('obrigacoes', ObrigacaoController::class);

    // Tarefas
    Route::resource('tarefas', TarefaController::class);
    Route::patch('tarefas/{tarefa}/concluir', [TarefaController::class, 'concluir'])->name('tarefas.concluir');

    // Financeiro
    Route::get('financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');
    Route::get('financeiro/contas-receber', [FinanceiroController::class, 'contasReceber'])->name('financeiro.contas-receber');
    Route::post('financeiro/contas-receber', [FinanceiroController::class, 'storeContaReceber'])->name('financeiro.cr.store');
    Route::patch('financeiro/contas-receber/{conta}/baixar', [FinanceiroController::class, 'baixarContaReceber'])->name('financeiro.cr.baixar');
    Route::delete('financeiro/contas-receber/{conta}', [FinanceiroController::class, 'destroyContaReceber'])->name('financeiro.cr.destroy');
    Route::get('financeiro/contas-pagar', [FinanceiroController::class, 'contasPagar'])->name('financeiro.contas-pagar');
    Route::post('financeiro/contas-pagar', [FinanceiroController::class, 'storeContaPagar'])->name('financeiro.cp.store');
    Route::patch('financeiro/contas-pagar/{conta}/baixar', [FinanceiroController::class, 'baixarContaPagar'])->name('financeiro.cp.baixar');
    Route::delete('financeiro/contas-pagar/{conta}', [FinanceiroController::class, 'destroyContaPagar'])->name('financeiro.cp.destroy');

    // Certidões
    Route::resource('certidoes', CertidaoController::class);

    // Certificados Digitais
    Route::resource('certificados', CertificadoDigitalController::class);

    // Documentos
    Route::resource('documentos', DocumentoController::class);

    // Notificações
    Route::get('notificacoes', [NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::patch('notificacoes/{notificacao}/ler', [NotificacaoController::class, 'marcarLida'])->name('notificacoes.ler');
    Route::post('notificacoes/ler-todas', [NotificacaoController::class, 'marcarTodasLidas'])->name('notificacoes.ler-todas');

    // Demandas
    Route::resource('demandas', DemandaController::class);
    Route::post('demandas/{demanda}/comentarios', [DemandaController::class, 'storeComentario'])->name('demandas.comentarios.store');
    Route::patch('demandas/{demanda}/checklist/{item}', [DemandaController::class, 'toggleChecklist'])->name('demandas.checklist.toggle');
    Route::post('demandas/{demanda}/checklist', [DemandaController::class, 'storeChecklist'])->name('demandas.checklist.store');

    // Projetos Internos
    Route::resource('projetos', ProjetoInternoController::class);

    // Indicadores
    Route::get('indicadores', [IndicadorController::class, 'index'])->name('indicadores.index');
    Route::get('indicadores/dados', [IndicadorController::class, 'dados'])->name('indicadores.dados');

    // Usuários (apenas admin)
    Route::middleware('check.role:admin')->group(function () {
        Route::resource('usuarios', UsuarioPainelController::class);
        Route::patch('usuarios/{usuario}/toggle-ativo', [UsuarioPainelController::class, 'toggleAtivo'])->name('usuarios.toggle-ativo');
        Route::post('usuarios/{usuario}/reset-senha', [UsuarioPainelController::class, 'resetSenha'])->name('usuarios.reset-senha');
    });

    // Configurações (admin + gestor)
    Route::middleware('check.role:admin,gestor')->group(function () {
        Route::get('configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');
        Route::post('configuracoes', [ConfiguracaoController::class, 'update'])->name('configuracoes.update');
    });
});
