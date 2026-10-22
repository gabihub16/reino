<?php
// Página administrativa que lista os produtos cadastrados no cardápio.
require_once __DIR__ . '/../login/verificadm.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">

</head>

<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <main class="container">
        <h1>Cardápio</h1>
        <!-- Lista os produtos cadastrados para consulta pelo administrador. -->
        <?php listar_produtos($conexao); ?>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>