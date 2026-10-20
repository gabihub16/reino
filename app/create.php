<?php 
require_once __DIR__ . '/../login/verificadm.php';
require_once __DIR__ . '/../includes/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">

</head>
<body>
    <br>
    <h1>Cadastro de Produtos</h1>
    <?php include __DIR__ .'/../includes/header.php'; ?>
    <br>
    <hr>
    <br>
    <form action="" method="POST">
        <label for="nome">Nome do produto: </label>
        <input type="text" name="nome" id="nome" required><br>

        <label for="marca">Marca: </label>
        <input type="text" name="marca" id="marca" required><br> 

        <aside class="sidebar">
            <label for="categoria">Categoria do produto:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione uma categoria</option>
                <option value="hamburgueres">Hambúrgueres</option>
                <option value="hot-dogs">Hot dogs</option>
                <option value="lanches">Lanches prensados</option>
                <option value="combos">Combos</option>
                <option value="acompanhamentos">Acompanhamentos</option>
                <option value="bebidas">Bebidas</option>
                <option value="molhos">Molhos</option>
            </select>
        </aside>

        <label for="descricao">Descrição: </label>
        <textarea name="descricao" id="descricao" rows="4" cols="40" placeholder="Digite uma Descrição Breve do Produto" required></textarea><br>
        
        <label for="preco">Preço (R$): </label>
        <input type="number" name="preco" id="preco" step="0.01" min="0" required><br>

        <label for="ativo">Disponibilidade do produto:</label>
        <select name="ativo" id="ativo" required>
            <option value="1" selected>Disponível</option>
            <option value="0">Inativo</option>
        </select><br>

        <br>

        <input type="reset" value="Limpar">
        <input type="submit" value="Cadastrar">
    </form>

    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            cadastrar_produto(
                $conexao, 
        $_POST['nome'] ?? '',
        $_POST['marca'] ?? '',
        $_POST['categoria'] ?? '',
        $_POST['descricao'] ?? '',
        $_POST['preco'] ?? '',
        $_POST['ativo'] ?? '1'
    );
}
        
        include __DIR__ . '/../includes/footer.php'; 
        ?>
</body>
</html>