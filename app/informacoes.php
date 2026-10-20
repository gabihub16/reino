<?php
require_once __DIR__ . '/../includes/configuracao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$perfil = $_SESSION['perfil'] ?? '';

// Só usa o carrinho se as funções já existirem
$tem_carrinho = false;
if ($perfil === 'cliente') {
    require_once __DIR__ . '/../includes/functions.php';
    $tem_carrinho = function_exists('contar_itens_carrinho') && isset($conexao);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="O Reino Burguer é uma hamburgueria cristã. Conheça nosso cardápio.">
    <title>Sobre o Reino Burguer</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= url('css/inform.css') ?>">
</head>
<body>

<?php
if ($perfil === 'admin') {
    include __DIR__ . '/../includes/header.php';
} elseif ($perfil === 'cliente') {
    ?>
    <header class="header-publico">
        <a class="header-brand" href="<?= url('index.php') ?>">
            <img src="<?= url('assets/reino-burguer-logo.png') ?>" alt="Reino Burguer">
        </a>
        <nav>
            <a href="<?= url('index.php') ?>">Início</a>
            <a href="<?= url('app/informacoes.php') ?>" aria-current="page">Sobre</a>
            <?php if ($tem_carrinho): ?>
                <a href="<?= url('mpedidos/carrinho.php') ?>">
                    Carrinho (<?= contar_itens_carrinho($conexao, (int) $_SESSION['id']) ?>)
                </a>
            <?php endif; ?>
            <a href="<?= url('login/logout.php') ?>">Sair (Logout)</a>
        </nav>
    </header>
    <?php
} else {
    ?>
    <header class="header-publico">
        <a class="header-brand" href="<?= url('index.php') ?>">
            <img src="<?= url('assets/reino-burguer-logo.png') ?>" alt="Reino Burguer">
        </a>
        <nav>
            <a href="<?= url('index.php') ?>">Início</a>
            <a href="<?= url('app/informacoes.php') ?>" aria-current="page">Sobre</a>
            <a href="<?= url('login/login.php') ?>">Login / Entrar</a>
            <a href="<?= url('login/cadastrar.php') ?>">Cadastrar Cliente</a>
        </nav>
    </header>
    <?php
}
?>

    <main class="sobre-pagina-simples">
        <section class="sobre-resumo" aria-labelledby="titulo-sobre">
            <h1 id="titulo-sobre">Sobre o Reino Burguer 🍔👑</h1>
            <p>O Reino Burguer nasceu com o propósito de oferecer muito mais do que hambúrgueres: queremos proporcionar uma experiência única, unindo sabor, qualidade e fé. Inspirados em princípios cristãos, acreditamos que cada detalhe pode transmitir amor, alegria e propósito.</p>
            <p>Com um cardápio criativo, ingredientes selecionados e nomes inspirados na fé, buscamos transformar cada pedido em um momento especial.</p>
            <p class="sobre-slogan"><strong>Reino Burguer – Quando tem propósito, tudo se torna extraordinário!</strong></p>
            <a class="btn btn-primary" href="<?= url('index.php') ?>">Ir para o cardápio</a>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>