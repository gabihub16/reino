<?php
require_once __DIR__ . '/../includes/functions.php';

$resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = cadastrar_cliente(
        $conexao,
        $_POST['nome'] ?? '',
        $_POST['email'] ?? '',
        $_POST['senha'] ?? '',
        $_POST['telefone'] ?? ''
    );
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Usuário</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
        <h1>Cadastro de Clientes</h1>
        
        <br>
        <form action="<?= htmlspecialchars(url('login/cadastrar.php'), ENT_QUOTES, 'UTF-8') ?>" method="post">
            
            <label for="nome">Digite seu Nome: </label>
            <input type="text" name="nome" id="nome" placeholder="Seu nome completo" required><br>

            <label for="email">Digite seu E-mail: </label>
            <input type="email" name="email" id="email" placeholder="Seu e-mail" required><br>
            
            <label for="telefone">Digite seu Telefone: </label>
            <input type="tel" name="telefone" id="telefone" placeholder="Seu telefone: (11) 99999-9999" maxlength="15" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" required><br>

            <label for="senha">Digite sua Senha: </label>
            <input type="password" name="senha" id="senha" placeholder="Sua senha" required><br>

            <input type="submit" value="Fazer Cadastro">
        </form>
        <?php if ($resultado !== null): ?>
            <p class="<?= $resultado['sucesso'] ? 'alert alert-success' : 'alert alert-danger' ?>" role="status">
                <?= htmlspecialchars($resultado['mensagem'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
            </p>
        <?php endif; ?>
        <br>
        <a href="<?= htmlspecialchars(url('login/login.php'), ENT_QUOTES, 'UTF-8') ?>">Fazer Login</a>
    
    <?php
        include __DIR__ .'/../includes/footer.php'; 
    ?>


</body>
</html>
