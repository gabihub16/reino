<?php
// Recebe a solicitação do cliente para adicionar um produto e retorna com o resultado.
require_once __DIR__ . '/../login/verficacli.php';   // exige login
require_once __DIR__ . '/../includes/functions.php';

// Só aceita POST e só para perfil "cliente"
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_SESSION['perfil'] ?? '') !== 'cliente') {
    header("Location: " . url('index.php'));
    exit();
}

$produto_id = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
$ok = $produto_id && adicionar_ao_carrinho($conexao, (int) $_SESSION['id'], $produto_id);

$resultado = $ok ? 'ok' : 'erro';
$retorno = ($_POST['retorno'] ?? '') === 'carrinho'
    ? url('mpedidos/carrinho.php?adicionado=') . $resultado
    : url('index.php?carrinho=') . $resultado;

header("Location: " . $retorno);
exit();
