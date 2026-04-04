<?php
/**
 * Bridge: public_html/api/index.php → ~/laravel/
 *
 * Este arquivo fica em public_html/api/index.php
 * O Laravel completo fica em ~/laravel/ (fora do public_html)
 *
 * O caminho ../../laravel sobe de:
 *   public_html/api/   → public_html/   → home/user/   → laravel/
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Caminho absoluto para o Laravel (dois níveis acima de public_html/api/)
$laravelBase = dirname(dirname(__DIR__)) . '/laravel';

// Modo de manutenção
if (file_exists($maintenance = $laravelBase . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Autoloader do Composer
require $laravelBase . '/vendor/autoload.php';

// Inicializa e processa a requisição
(require_once $laravelBase . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
