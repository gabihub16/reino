<?php
require_once __DIR__ . '/../includes/configuracao.php';
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Limpa a sessão e detroi
$_SESSION = array();
session_destroy();
// Redireciona para index.php
header("Location: " . url('index.php'));
    exit();
?>
