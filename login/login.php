<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/functions.php';

$erro = '';

// Processamento do formulário no TOPO (antes de qualquer HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $cliente = consulta_cliente($conexao, $email);
    
    if ($cliente && ($senha === $cliente['senha'] || password_verify($senha, $cliente['senha']))) {
        
        session_regenerate_id(true);
        $_SESSION['id']     = $cliente['id'];
        $_SESSION['nome']   = $cliente['nome'];
        $_SESSION['perfil'] = $cliente['perfil'];

        header("Location: " . url('index.php'));
        exit();
        
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Reino Burguer</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>

    <h1>Faça Login</h1>
    <br>

    <?php if (!empty($erro)): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form action="<?= htmlspecialchars(url('login/login.php'), ENT_QUOTES, 'UTF-8') ?>" method="post">
        <label for="email">Digite seu E-mail: </label>
        <input type="email" name="email" id="email" placeholder="Seu e-mail" required><br>

        <label for="senha">Digite sua Senha: </label>
        <input type="password" name="senha" id="senha" placeholder="Sua senha" required><br>

        <input type="submit" value="Fazer Login">
    </form>

    <br>
    <a href="<?= url('login/cadastrar.php') ?>">Ainda não possui login? Cadastre-se</a>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>