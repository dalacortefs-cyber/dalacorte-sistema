<?php
// DALACORTE SETUP RUNNER - APAGAR APÓS USO
if (!isset($_GET['run'])) { ?>
<!DOCTYPE html>
<html>
<head><title>Dalacorte Setup</title>
<style>body{font-family:monospace;background:#1e1e1e;color:#00ff00;padding:20px;}
button{background:#0070f3;color:white;border:none;padding:12px 24px;font-size:16px;cursor:pointer;border-radius:6px;margin:5px;}
button:hover{background:#0051cc;} h2{color:#fff;}</style>
</head>
<body>
<h2>🚀 Dalacorte Financial Solutions — Setup</h2>
<p>Clique nos botões em ordem:</p>
<form method="GET">
<button name="run" value="composer">1. Composer Install</button>
<button name="run" value="key">2. Gerar APP_KEY</button>
<button name="run" value="jwt">3. Gerar JWT Secret</button>
<button name="run" value="publish">4. Publicar Configs</button>
<button name="run" value="migrate">5. Rodar Migrations</button>
<button name="run" value="seed">6. Rodar Seeders</button>
<button name="run" value="optimize">7. Otimizar</button>
</form>
</body></html>
<?php
    exit;
}

$base = dirname(__DIR__);
chdir($base);

$commands = [
    'composer' => "composer install --no-dev --optimize-autoloader 2>&1",
    'key'      => "php artisan key:generate --force 2>&1",
    'jwt'      => "php artisan jwt:secret --force 2>&1",
    'publish'  => "php artisan vendor:publish --provider=\"PHPOpenSourceSaver\\JWTAuth\\Providers\\LaravelServiceProvider\" --force 2>&1 && php artisan vendor:publish --provider=\"Spatie\\Permission\\PermissionServiceProvider\" --force 2>&1",
    'migrate'  => "php artisan migrate --force 2>&1",
    'seed'     => "php artisan db:seed --force 2>&1",
    'optimize' => "php artisan config:cache 2>&1 && php artisan route:cache 2>&1 && php artisan storage:link 2>&1",
];

$run = $_GET['run'];
if (!array_key_exists($run, $commands)) die('Comando inválido.');

echo "<pre style='background:#1e1e1e;color:#00ff00;padding:20px;font-size:13px;'>";
echo "Executando: <strong style='color:#fff'>{$run}</strong>\n\n";
flush();

$output = shell_exec($commands[$run]);
echo htmlspecialchars($output);
echo "\n\n✅ Concluído. <a href='setup.php' style='color:#0070f3'>← Voltar</a>";
echo "</pre>";
