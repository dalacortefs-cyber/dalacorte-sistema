<?php
/**
 * DFS Setup — roda composer + artisan via exec()
 * DELETE após usar!
 */

define('SETUP_TOKEN', 'DFS@Setup2024');

if (($_GET['token'] ?? '') !== SETUP_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:monospace;color:red">Acesso negado. Use ?token=DFS@Setup2024</h2>');
}

set_time_limit(300);
ini_set('max_execution_time', 300);

// Detectar raiz do projeto (um nível acima de public/)
$root = dirname(__DIR__);

// Detectar binário PHP correto
function findPhp(): string {
    foreach (['php', 'php8.2', 'php8.1', 'php8.0', '/usr/local/bin/php', '/usr/bin/php'] as $bin) {
        $out = shell_exec("$bin -r 'echo PHP_MAJOR_VERSION;' 2>/dev/null");
        if ($out && (int)$out >= 8) return $bin;
    }
    return 'php';
}

// Detectar composer
function findComposer(string $root): string {
    if (file_exists("$root/composer.phar")) return findPhp()." $root/composer.phar";
    $out = shell_exec('which composer 2>/dev/null');
    if ($out) return trim($out);
    return findPhp()." $root/composer.phar"; // vai baixar abaixo
}

function run(string $cmd): array {
    $output = [];
    $exitCode = 0;
    exec($cmd . ' 2>&1', $output, $exitCode);
    return ['cmd' => $cmd, 'exit' => $exitCode, 'out' => implode("\n", $output)];
}

$php      = findPhp();
$artisan  = "$php $root/artisan";

$results = [];

// 1. Info do ambiente
$results[] = ['cmd' => 'PHP Version', 'exit' => 0, 'out' => shell_exec("$php -v 2>&1")];
$results[] = ['cmd' => 'Diretório raiz', 'exit' => 0, 'out' => $root];
$results[] = ['cmd' => 'vendor/ existe?', 'exit' => 0, 'out' => is_dir("$root/vendor") ? 'SIM' : 'NÃO — composer install necessário'];

// 2. Baixar composer.phar se não existir
if (!file_exists("$root/composer.phar") && !shell_exec('which composer 2>/dev/null')) {
    $results[] = run("cd $root && $php -r \"copy('https://getcomposer.org/installer', 'composer-setup.php'); require 'composer-setup.php'; unlink('composer-setup.php');\"");
}

$composer = findComposer($root);

// 3. Composer install (só se vendor não existir)
if (!is_dir("$root/vendor")) {
    $results[] = run("cd $root && $composer install --no-dev --optimize-autoloader --no-interaction 2>&1");
} else {
    $results[] = ['cmd' => 'composer install', 'exit' => 0, 'out' => 'vendor/ já existe, pulando.'];
}

// 4. Artisan commands
$artisanCmds = [
    'key:generate --force',
    'jwt:secret --force',
    'migrate --force',
    'db:seed --class=AdminSeeder --force',
    'config:clear',
    'route:clear',
    'view:clear',
    'cache:clear',
];

foreach ($artisanCmds as $cmd) {
    $results[] = run("cd $root && $artisan $cmd");
}

?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>DFS Setup</title>
<style>
  body { font-family: monospace; background: #0f1923; color: #e2e8f0; padding: 2rem; max-width: 900px; margin: 0 auto; }
  h1 { color: #8B6914; margin-bottom: 0.25rem; }
  p { color: #94a3b8; margin-top: 0; }
  .cmd { margin: 0.75rem 0; border: 1px solid #1e3a40; border-radius: 6px; overflow: hidden; }
  .cmd-header { background: #1B4A52; padding: 0.5rem 1rem; display: flex; justify-content: space-between; align-items: center; }
  .cmd-name { font-weight: bold; }
  .cmd-body { padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8rem; color: #cbd5e1; border-top: 1px solid #1e3a40; }
  .ok { color: #34d399; font-weight: bold; }
  .fail { color: #f87171; font-weight: bold; }
  .warn { background: rgba(139,105,20,0.2); border: 1px solid #8B6914; color: #fbbf24; padding: 1rem; border-radius: 6px; margin-top: 2rem; }
  .success { background: rgba(52,211,153,0.1); border: 1px solid #34d399; color: #34d399; padding: 1rem; border-radius: 6px; margin-top: 1rem; }
  a { color: #8B6914; }
</style>
</head>
<body>
<h1>DFS — Setup do Sistema</h1>
<p>Executado em: <?= date('d/m/Y H:i:s') ?></p>

<?php
$allOk = true;
foreach ($results as $r):
    $ok = $r['exit'] === 0;
    if (!$ok && !in_array($r['cmd'], ['Diretório raiz', 'PHP Version', 'vendor/ existe?'])) $allOk = false;
?>
<div class="cmd">
  <div class="cmd-header">
    <span class="cmd-name"><?= htmlspecialchars($r['cmd']) ?></span>
    <span class="<?= $ok ? 'ok' : 'fail' ?>"><?= $ok ? '✓ OK' : '✗ ERRO' ?></span>
  </div>
  <?php if (trim($r['out'])): ?>
  <div class="cmd-body"><?= htmlspecialchars(trim($r['out'])) ?></div>
  <?php endif; ?>
</div>
<?php endforeach; ?>

<?php if ($allOk): ?>
<div class="success">
  ✓ Setup concluído com sucesso! Login: <strong>dalacortefs@gmail.com</strong> / <strong>DFS@Admin2024</strong>
</div>
<?php endif; ?>

<div class="warn">
  ⚠️ <strong>DELETE</strong> este arquivo agora: <code>public/setup_dfs.php</code>
</div>

<p style="margin-top:1.5rem">
  <a href="/login">→ Ir para o login do painel</a>
  &nbsp;|&nbsp;
  <a href="?token=DFS@Setup2024">↺ Rodar novamente</a>
</p>
</body>
</html>
