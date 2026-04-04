# Deploy — Dalacorte Financial Solutions

## Passo 1: Gerar chaves (no servidor via SSH ou File Manager)

```bash
php artisan key:generate
php artisan jwt:secret
```

## Passo 2: Rodar migrations

```bash
# Se banco está vazio:
php artisan migrate

# Se quiser recriar tudo (APAGA DADOS):
php artisan migrate:fresh
```

## Passo 3: Criar admin e escritório

```bash
php artisan db:seed --class=AdminSeeder
```

Resultado: `dalacortefs@gmail.com` / `DFS@Admin2024`

## Passo 4: Importar dados do Base44 (executar só uma vez)

1. Adicionar ao `.env`:
   ```
   BASE44_API_KEY=sua_chave_aqui
   BASE44_APP_ID=698cd879b364717cc2acd220
   ```

2. Testar sem importar:
   ```bash
   php artisan base44:importar --dry-run
   ```

3. Importar de verdade:
   ```bash
   php artisan base44:importar
   ```

4. Após confirmar dados no phpMyAdmin, remover do `.env`:
   ```
   # BASE44_API_KEY=...   ← remover
   # BASE44_APP_ID=...    ← remover
   ```

## Passo 5: Acesso

- Login: `https://dalacortefs.com.br/login`
- Painel: `https://dalacortefs.com.br/painel`

## Configuração de sessão no Hostgator (compartilhado)

No `.env`, confirmar:
```
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
CACHE_STORE=file
```

No `.htaccess` da `public/`, garantir:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Permissões (Hostgator)

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env
```
