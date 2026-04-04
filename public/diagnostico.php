<?php
// Diagnóstico simples — sem dependências
header('Content-Type: text/html; charset=utf-8');

$root = dirname(__DIR__);

$checks = [
    'PHP Version'             => PHP_VERSION,
    'Diretório public/'       => __DIR__,
    'Diretório raiz (/../)'   => $root,
    'vendor/ existe?'         => is_dir("$root/vendor") ? '✓ SIM' : '✗ NÃO',
    'artisan existe?'         => file_exists("$root/artisan") ? '✓ SIM' : '✗ NÃO',
    '.env existe?'            => file_exists("$root/.env") ? '✓ SIM' : '✗ NÃO (CRÍTICO)',
    'composer.json existe?'   => file_exists("$root/composer.json") ? '✓ SIM' : '✗ NÃO',
    'exec() habilitado?'      => function_exists('exec') && !in_array('exec', array_map('trim', explode(',', ini_get('disable_functions')))) ? '✓ SIM' : '✗ NÃO (bloqueado)',
    'shell_exec() habilitado?'=> function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions')))) ? '✓ SIM' : '✗ NÃO (bloqueado)',
    'Funções desabilitadas'   => ini_get('disable_functions') ?: '(nenhuma)',
    'PHP SAPI'                => PHP_SAPI,
    'Usuário do processo'     => function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : get_current_user(),
];

// Conteúdo do .env (apenas variáveis não sensíveis)
$envPreview = '';
if (file_exists("$root/.env")) {
    $lines = file("$root/.env");
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, 'DB_PASSWORD') || str_starts_with($line, 'JWT') || str_starts_with($line, 'APP_KEY') || str_starts_with($line, 'CLAUDE')) continue;
        $envPreview .= $line . "\n";
    }
}

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>DFS Diagnóstico</title>
<style>body{font-family:monospace;background:#0f1923;color:#e2e8f0;padding:2rem}
h1{color:#8B6914}table{border-collapse:collapse;width:100%}
td{padding:.5rem 1rem;border-bottom:1px solid #1e3a40}
td:first-child{color:#94a3b8;width:40%}
.ok{color:#34d399}.fail{color:#f87171}
pre{background:#1a2a30;padding:1rem;border-radius:6px;font-size:.8rem;overflow:auto}</style>
</head><body><h1>DFS — Diagnóstico do Servidor</h1>
<table>';

foreach ($checks as $label => $value) {
    $cls = str_contains((string)$value, '✗') ? 'fail' : (str_contains((string)$value, '✓') ? 'ok' : '');
    echo "<tr><td>$label</td><td class='$cls'>" . htmlspecialchars((string)$value) . "</td></tr>";
}

echo '</table>';

if ($envPreview) {
    echo '<h2 style="color:#8B6914;margin-top:2rem">.env (parcial)</h2><pre>' . htmlspecialchars($envPreview) . '</pre>';
}

// Listar arquivos na raiz
echo '<h2 style="color:#8B6914;margin-top:2rem">Arquivos na raiz do projeto</h2><pre>';
$files = scandir($root);
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    $type = is_dir("$root/$f") ? '[DIR] ' : '      ';
    echo $type . $f . "\n";
}
echo '</pre></body></html>';
