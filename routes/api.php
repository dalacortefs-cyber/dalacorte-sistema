<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtratoController;
use App\Http\Controllers\IaController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\PortalClienteController;
use App\Http\Controllers\TarefaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Dalacorte Financial Solutions
|--------------------------------------------------------------------------
*/

// ─── Públicas ────────────────────────────────────────────────────────────────

Route::prefix('v1')->group(function () {

    // Auth
    Route::post('auth/login', [AuthController::class, 'login']);

    // Portal público: notícias
    Route::get('portal/noticias', [NoticiaController::class, 'listarPortal']);
    Route::get('portal/noticias/{slug}', [NoticiaController::class, 'mostrarPortal']);

    // Vagas públicas
    Route::get('vagas', [CandidaturaController::class, 'listarVagas']);

    // Candidatura pública (sem auth)
    Route::post('candidaturas', [CandidaturaController::class, 'candidatar']);

    // ─── Autenticadas ────────────────────────────────────────────────────────
    Route::middleware('jwt.auth')->group(function () {

        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('register', [AuthController::class, 'register']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me', [AuthController::class, 'me']);
            Route::post('alterar-senha', [AuthController::class, 'alterarSenha']);
        });

        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::get('kpis', [DashboardController::class, 'kpis']);
            Route::get('ia', [DashboardController::class, 'resumoComIA']);
            Route::get('grafico/clientes', [DashboardController::class, 'graficoClientes']);
            Route::get('grafico/financeiro', [DashboardController::class, 'graficoFinanceiro']);
        });

        // Clientes
        Route::prefix('clientes')->group(function () {
            Route::get('/', [ClienteController::class, 'index']);
            Route::post('/', [ClienteController::class, 'store']);
            Route::get('{cliente}', [ClienteController::class, 'show']);
            Route::put('{cliente}', [ClienteController::class, 'update']);
            Route::delete('{cliente}', [ClienteController::class, 'destroy']);
            Route::post('{cliente}/sincronizar-onvio', [ClienteController::class, 'sincronizarOnvio']);
        });

        // Extratos
        Route::prefix('extratos')->group(function () {
            Route::get('/', [ExtratoController::class, 'index']);
            Route::post('/', [ExtratoController::class, 'upload']);
            Route::get('{extrato}', [ExtratoController::class, 'show']);
            Route::post('{extrato}/analisar-ia', [ExtratoController::class, 'analisarIa']);
            Route::get('{extrato}/pdf', [ExtratoController::class, 'exportarPdf']);
            Route::delete('{extrato}', [ExtratoController::class, 'destroy']);
        });

        // Tarefas
        Route::prefix('tarefas')->group(function () {
            Route::get('/', [TarefaController::class, 'index']);
            Route::post('/', [TarefaController::class, 'store']);
            Route::get('{tarefa}', [TarefaController::class, 'show']);
            Route::put('{tarefa}', [TarefaController::class, 'update']);
            Route::delete('{tarefa}', [TarefaController::class, 'destroy']);
            Route::patch('{tarefa}/concluir', [TarefaController::class, 'concluir']);
        });

        // Leads / CRM
        Route::prefix('leads')->group(function () {
            Route::get('/', [LeadController::class, 'index']);
            Route::post('/', [LeadController::class, 'store']);
            Route::get('funil', [LeadController::class, 'funil']);
            Route::get('{lead}', [LeadController::class, 'show']);
            Route::put('{lead}', [LeadController::class, 'update']);
            Route::delete('{lead}', [LeadController::class, 'destroy']);
            Route::post('{lead}/classificar-ia', [LeadController::class, 'classificarIA']);
        });

        // IA / Chat
        Route::prefix('ia')->group(function () {
            Route::post('chat', [IaController::class, 'chat']);
            Route::post('resumo-dashboard', [IaController::class, 'resumoDashboard']);
            Route::post('analisar-texto', [IaController::class, 'analisarTexto']);
        });

        // Notícias (gestão)
        Route::prefix('noticias')->group(function () {
            Route::get('/', [NoticiaController::class, 'index']);
            Route::post('/', [NoticiaController::class, 'store']);
            Route::get('{noticia}', [NoticiaController::class, 'show']);
            Route::put('{noticia}', [NoticiaController::class, 'update']);
            Route::delete('{noticia}', [NoticiaController::class, 'destroy']);
            Route::patch('{noticia}/publicar', [NoticiaController::class, 'publicar']);
        });

        // Portal do Cliente
        Route::prefix('portal')->middleware('role:cliente')->group(function () {
            Route::get('meus-dados', [PortalClienteController::class, 'meusDados']);
            Route::get('meus-extratos', [PortalClienteController::class, 'meusExtratos']);
            Route::get('meus-extratos/{id}', [PortalClienteController::class, 'verExtrato']);
            Route::post('assistente', [PortalClienteController::class, 'assistenteIA']);
            Route::get('noticias', [PortalClienteController::class, 'noticias']);
            Route::get('resumo-financeiro', [PortalClienteController::class, 'resumoFinanceiro']);
        });

        // RH — Vagas e Candidaturas
        Route::prefix('rh')->group(function () {
            Route::get('vagas', [CandidaturaController::class, 'listarVagas']);
            Route::post('vagas', [CandidaturaController::class, 'criarVaga']);
            Route::put('vagas/{vaga}', [CandidaturaController::class, 'atualizarVaga']);
            Route::get('candidaturas', [CandidaturaController::class, 'listarCandidaturas']);
            Route::patch('candidaturas/{candidatura}/status', [CandidaturaController::class, 'atualizarStatus']);
        });

    });
});

// Health check
Route::get('health', fn() => response()->json([
    'status'  => 'ok',
    'app'     => config('app.name'),
    'version' => '1.0.0',
    'time'    => now()->toISOString(),
]));
