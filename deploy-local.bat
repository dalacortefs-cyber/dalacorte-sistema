@echo off
title Deploy dalacortefs.com.br

echo.
echo ====================================
echo   Deploy Dalacorte Financial
echo ====================================
echo.

:: 1. Build do frontend
echo [1/4] Buildando o frontend Next.js...
cd /d "%~dp0frontend"
call npm run build
if %errorlevel% neq 0 (
    echo ERRO: Build falhou. Deploy cancelado.
    pause
    exit /b 1
)
echo Build concluido!
echo.

:: 2. Voltar para raiz
cd /d "%~dp0"

:: 3. Git add + commit
echo [2/4] Verificando alteracoes...
git add .

git diff --cached --quiet
if %errorlevel% equ 0 (
    echo Nenhuma alteracao encontrada. Nada a enviar.
    pause
    exit /b 0
)

echo.
set /p MSG=[3/4] Mensagem do commit:
if "%MSG%"=="" set MSG=atualizacao do site

git commit -m "%MSG%"
echo.

:: 4. Push
echo [4/4] Enviando para o GitHub...
git push origin main
if %errorlevel% neq 0 (
    echo ERRO: Falha ao enviar para o GitHub.
    pause
    exit /b 1
)

echo.
echo ====================================
echo   Enviado! Site no ar em ~2 min.
echo ====================================
echo.
pause
