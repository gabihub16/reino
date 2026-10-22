<?php
// Funções compartilhadas para detectar a raiz pública e montar URLs do sistema.
// Descobre a raiz pública do projeto para funcionar tanto em /reino quanto na raiz do servidor.
function base_url($novo_caminho = null)
{
    static $base = null;

    if ($novo_caminho !== null) {
        $base = rtrim(str_replace('\\', '/', $novo_caminho), '/');
        return $base;
    }

    if ($base !== null) {
        return $base;
    }

    $script = parse_url($_SERVER['SCRIPT_NAME'] ?? '/', PHP_URL_PATH) ?: '/';
    $ultima_barra = strrpos($script, '/');
    $diretorio = $ultima_barra === false ? '' : substr($script, 0, $ultima_barra);
    $pastas_internas = ['app', 'login', 'mpedidos'];
    $ultima_barra = strrpos($diretorio, '/');
    $nome_diretorio = $ultima_barra === false
        ? $diretorio
        : substr($diretorio, $ultima_barra + 1);

    if (in_array($nome_diretorio, $pastas_internas, true)) {
        $ultima_barra = strrpos($diretorio, '/');
        $diretorio = $ultima_barra === false
            ? ''
            : substr($diretorio, 0, $ultima_barra);
    }

    $base = rtrim($diretorio, '/');
    return $base;
}

// Permite substituir a raiz detectada quando o servidor exigir uma configuração específica.
function definir_base_url($novo_caminho)
{
    base_url($novo_caminho);
}

// Monta uma URL absoluta a partir da raiz pública do projeto.
function url($caminho = '')
{
    return base_url() . '/' . ltrim($caminho, '/');
}
