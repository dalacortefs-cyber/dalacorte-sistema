<?php
/**
 * Script de setup único — DFS
 * ATENÇÃO: DELETE este arquivo após usar!
 */

// Senha de proteção — mude se quiser
define('SETUP_TOKEN', 'DFS@Setup2024');

if (($_GET['token'] ?? '') !== SETUP_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:monospace;color:red">Acesso negado. Use ?token=DFS@Setup2024</h2>');
}

// Bootstrap do Laravel
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$output = [];
$errors = [];

function runCommand($kernel, $cmd, $params = []) {
    $exitCode = $kernel->call($cmd, $params);
    $out = $kernel->output();
    return ['cmd' => $cmd, 'exit' => $exitCode, 'out' => trim($out)];
}

// Comandos a executar
$commands = [
    ['key:generate',    ['--force' => true]],
    ['jwt:secret',      ['--force' => true]],
    ['migrate',         ['--force' => true]],
    ['db:seed',         ['--class' => 'AdminSeeder', '--force' => true]],
    ['config:clear',    []],
    ['route:clear',     []],
    ['view:clear',      []],
    ['cache:clear',     []],
];

$results = [];
foreach ($commands as [$cmd, $params]) {
    try {
        $results[] = runCommand($kernel, $cmd, $params);
    } catch (Throwable $e) {
        $results[] = ['cmd' => $cmd, 'exit' => 1, 'out' => 'ERRO: '.$e->getMessage()];
    }
}

$kernel->terminate(null, 0);

?><!DOCTYPE html>
<html>
<head>
<title>DFS Setup</title>
<style>
  body { font-family: monospace; background: #0f1923; color: #e2e8f0; padding: 2rem; }
  h1 { color: #8B6914; }
  .cmd { margin: 1rem 0; border: 1px solid #1B4A52; border-radius: 6px; overflow: hidden; }
  .cmd-header { background: #1B4A52; padding: 0.5rem 1rem; display: flex; justify-content: space-between; }
  .cmd-body { padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.85rem; }
  .ok { color: #34d399; }
  .fail { color: #f87171; }
  .warn { background: #7c3a00; color: #fbbf24; padding: 1rem; border-radius: 6px; margin-top: 2rem; font-size: 1rem; }
</style>
</head>
<body>
<h1>DFS — Setup do Sistema</h1>
<p style="color:#94a3b8">Executado em: <?= date('d/m/Y H:i:s') ?></p>

<?php foreach ($results as $r): ?>
<div class="cmd">
  <div class="cmd-header">
    <span>php artisan <?= htmlspecialchars($r['cmd']) ?></span>
    <span class="<?= $r['exit'] === 0 ? 'ok' : 'fail' ?>">
      <?= $r['exit'] === 0 ? '✓ OK' : '✗ ERRO (código '.$r['exit'].')' ?>
    </span>
  </div>
  <?php if ($r['out']): ?>
  <div class="cmd-body"><?= htmlspecialchars($r['out']) ?></div>
  <?php endif; ?>
</div>
<?php endforeach; ?>

<div class="warn">
  ⚠️ <strong>IMPORTANTE:</strong> Delete o arquivo <code>public/setup_dfs.php</code> agora que o setup foi executado!
</div>

<p style="margin-top:1.5rem">
  <a href="/login" style="color:#8B6914; font-size:1.1rem">→ Ir para o login do painel</a>
</p>
</body>
</html>
