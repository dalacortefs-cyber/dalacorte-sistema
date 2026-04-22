<?php
/**
 * Bridge: public_html/api/index.php -> ~/laravel/
 *
 * Este arquivo fica em public_html/api/index.php
 * O Laravel completo fica em ~/laravel/ (fora do public_html)
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Caminho absoluto para o Laravel (dois niveis acima de public_html/api/)
$laravelBase = dirname(dirname(__DIR__)) . '/laravel';

// CORRECAO DE ROTEAMENTO:
// O script esta em /api/index.php. O Laravel usa SCRIPT_NAME para calcular o
// base path e remove esse prefixo do REQUEST_URI antes de fazer o match de rotas.
// Como SCRIPT_NAME=/api/index.php, o Laravel removeria /api/ do URI, quebrando
// o match com rotas registradas com prefixo /api/ (via withRouting(api:...)).
// Fixamos SCRIPT_NAME para '/' e PHP_SELF para o REQUEST_URI.
$_SERVER['SCRIPT_NAME'] = '/';
$_SERVER['PHP_SELF']    = $_SERVER['REQUEST_URI'] ?? '/';

// Modo de manutencao
if (file_exists($maintenance = $laravelBase . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader do Composer
require $laravelBase . '/vendor/autoload.php';

// Inicializa e processa a requisicao
(require_once $laravelBase . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
