<?php
// Página administrativa que solicita o ID e remove o produto correspondente do cardápio.
require_once __DIR__ . '/../login/verificadm.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">

</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="admin-delete">
        <h1>Excluir Produto</h1>
        <!-- O administrador informa o ID do produto que deseja remover. -->
        <form action="" method="post">
            <label for="id">ID do produto: </label>
            <input type="number" name="id" id="id" min="1" required>
            <input type="submit" value="Excluir produto" class="btn-danger">
        </form>

        <?php
        // Processa a exclusão somente quando o formulário é enviado.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            deletar_produto($conexao, $_POST['id'] ?? '');
        }
        ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <br>
</body>
</html>