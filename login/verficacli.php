<?php
// Protege páginas do cliente: exige login e redireciona perfis sem permissão.
require_once __DIR__ . '/../includes/configuracao.php';
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['id'])){
    header("Location: " . url('login/login.php'));
    exit();
}

if (($_SESSION['perfil'] ?? '') !== 'cliente') {
    header("Location: " . url('index.php'));
    exit();
}
?>
