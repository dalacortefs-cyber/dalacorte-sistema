<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso — Dalacorte Financial Solutions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --teal:   #1B4A52;
            --gold:   #8B6914;
            --dark:   #0f1923;
            --darker: #0a1018;
            --card:   #162230;
            --border: rgba(255,255,255,0.08);
            --text:   #e2e8f0;
            --muted:  #94a3b8;
        }

        body {
            font-family: 'Georgia', serif;
            background: var(--darker);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(27,74,82,0.15) 0%, transparent 60%),
                radial-gradient(circle at 80% 20%, rgba(139,105,20,0.08) 0%, transparent 50%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-monogram {
            width: 72px;
            height: 72px;
            background: var(--teal);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 1rem;
            box-shadow: 0 8px 32px rgba(27,74,82,0.4);
        }

        .logo-area h1 {
            font-size: 1.3rem;
            color: var(--text);
            font-weight: normal;
            letter-spacing: 0.02em;
        }

        .logo-area p {
            font-size: 0.8rem;
            color: var(--muted);
            font-family: sans-serif;
            margin-top: 0.25rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 16px 48px rgba(0,0,0,0.4);
        }

        .card h2 {
            font-size: 1.1rem;
            font-weight: normal;
            color: var(--text);
            margin-bottom: 1.75rem;
            text-align: center;
        }

        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-family: sans-serif;
            font-size: 0.875rem;
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            font-family: sans-serif;
            font-size: 0.875rem;
            color: #86efac;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-family: sans-serif;
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: var(--text);
            font-family: sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            outline: none;
        }

        input:focus {
            border-color: var(--teal);
            background: rgba(27,74,82,0.1);
        }

        input.error-field {
            border-color: rgba(239,68,68,0.5);
        }

        .field-error {
            font-family: sans-serif;
            font-size: 0.8rem;
            color: #fca5a5;
            margin-top: 0.35rem;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--teal);
        }

        .remember-row label {
            font-family: sans-serif;
            font-size: 0.85rem;
            color: var(--muted);
            text-transform: none;
            letter-spacing: 0;
            margin: 0;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: var(--teal);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'Georgia', serif;
            font-size: 1rem;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            position: relative;
        }

        .btn-login:hover  { background: #245266; }
        .btn-login:active { transform: scale(0.98); }
        .btn-login:disabled { opacity: 0.6; cursor: not-allowed; }

        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-family: sans-serif;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .gold-line {
            width: 40px;
            height: 2px;
            background: var(--gold);
            margin: 0 auto 1.5rem;
            border-radius: 1px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-area">
            <div class="logo-monogram">DFS</div>
            <h1>Dalacorte Financial Solutions</h1>
            <p>Sistema de Gestão Interno</p>
        </div>

        <div class="card">
            <div class="gold-line"></div>
            <h2>Acesse sua conta</h2>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        class="{{ $errors->has('email') ? 'error-field' : '' }}"
                        placeholder="seu@email.com"
                    >
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        class="{{ $errors->has('password') ? 'error-field' : '' }}"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Manter-me conectado</label>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <span id="btnText">Entrar</span>
                    <div class="spinner" id="btnSpinner"></div>
                </button>
            </form>
        </div>

        <p class="footer-text">© {{ date('Y') }} Dalacorte Financial Solutions</p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function () {
            document.getElementById('btnText').style.display = 'none';
            document.getElementById('btnSpinner').style.display = 'block';
            document.getElementById('btnLogin').disabled = true;
        });
    </script>
</body>
</html>
