<?php
// Página administrativa para alterar os dados e a disponibilidade de um produto.
require_once __DIR__ . '/../login/verificadm.php';
require_once __DIR__ . '/../includes/functions.php';
?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">

</head>
<body>

    <h1>Atualizar Produtos</h1>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    

    <!-- Formulário com o ID do produto e os novos valores que serão gravados. -->
    <form action="" method="POST">
        <label for="id">ID do produto: </label>
        <input type="number" name="id" id="id" required><br>
        <br>

        <label for="nome">Nome do produto: </label>
        <input type="text" name="nome" id="nome" required><br>

        <label for="marca">Marca: </label>
        <input type="text" name="marca" id="marca" required><br> 

        <label for="categoria">Categoria do produto: </label>
        <select name="categoria" id="categoria" required>
            <option value="hamburgueres">Hambúrgueres</option>
            <option value="hot-dogs">Hot dogs</option>
            <option value="lanches">Lanches prensados</option>
            <option value="combos">Combos</option>
            <option value="acompanhamentos">Acompanhamentos</option>
            <option value="bebidas">Bebidas</option>
            <option value="molhos">Molhos</option>
        </select><br>

        <label for="descricao">Descrição: </label>
        <textarea name="descricao" id="descricao" rows="4" cols="40" placeholder="Digite uma Descrição Breve do Produto" required></textarea><br>
        
        <label for="preco">Preço (R$): </label>
        <input type="number" name="preco" id="preco" step="0.01" min="0" required><br>

        <label for="ativo">Disponibilidade do produto:</label>
        <select name="ativo" id="ativo" required>
            <option value="1">Disponível</option>
            <option value="0">Inativo</option>
        </select><br>

        <input type="reset" value="Limpar">
        <input type="submit" value="Atualizar" class="btn-edit">
    </form>

    <?php
    // Ao enviar o formulário, encaminha os campos preenchidos para a função de atualização.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        atualizar_produto(
            $conexao,
            $_POST['id'],
            $_POST['nome'],
            $_POST['marca'],
            $_POST['categoria'],
            $_POST['descricao'],
            $_POST['preco'],
            $_POST['ativo']
        );
    }
    // Inclui o rodapé compartilhado no fim da página.
    include __DIR__ . '/../includes/footer.php'; 
?>

</body>
</html>