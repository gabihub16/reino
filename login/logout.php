<?php
// Encerra a sessão atual e redireciona o usuário para a página inicial.
require_once __DIR__ . '/../includes/configuracao.php';
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Limpa a sessão e encerra a sessão do usuário
$_SESSION = array();
session_destroy();
// Redireciona para index.php
header("Location: " . url('index.php'));
    exit();
?>
