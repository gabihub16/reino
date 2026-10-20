<?php
require_once __DIR__ . '/../includes/configuracao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: " . url('login/login.php'));
    exit();
}

if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'admin') {
    header("Location: " . url('index.php'));
    exit();
}
