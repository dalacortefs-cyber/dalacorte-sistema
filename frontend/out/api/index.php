<?php
/**
 * Bridge: public_html/api/index.php → ~/laravel/
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$laravelBase = dirname(dirname(__DIR__)) . '/laravel';

if (file_exists($maintenance = $laravelBase . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravelBase . '/vendor/autoload.php';

(require_once $laravelBase . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
