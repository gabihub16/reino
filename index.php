<?php
// Página inicial: mostra o cardápio, oferece filtros e exibe indicadores para administradores.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/functions.php';

// Captura os filtros enviados pelo formulário GET
$busca     = trim($_GET['busca'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');
$ordem     = trim($_GET['ordem'] ?? '');

// Chama a função centralizada do functions.php
$produtos = buscar_produtos_filtrados($conexao, $busca, $categoria, $ordem);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reino Burguer</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
    <link rel="icon" type="image/png" href="<?= url('assets/reino-burguer-favicon.png') ?>">
</head>
<body>

    <?php 
if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin') {
    include __DIR__ . '/includes/header.php';
} 

elseif (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'cliente') {
    ?>
    <header class="header-publico">
        <a class="header-brand" href="<?= url('index.php') ?>">
            <img src="<?= url('assets/reino-burguer-logo.png') ?>" alt="Reino Burguer">
        </a>
        <nav>
            <a href="<?= url('index.php') ?>">Início</a> | <a href="<?= url('app/informacoes.php') ?>">Sobre</a>
            

            <a href="<?= url('mpedidos/carrinho.php') ?>">
                🛒 Carrinho (<?= contar_itens_carrinho($conexao, (int) $_SESSION['id']) ?>)
            </a> |
            <a href="<?= url('login/logout.php') ?>">Sair (Logout)</a>
        </nav>
    </header>
    <?php
} 
else {
    ?>
    <header class="header-publico header-visitante">
        <div class="header-visitante-inicio-grupo">
            <a class="header-brand" href="<?= url('index.php') ?>">
                <img src="<?= url('assets/reino-burguer-logo.png') ?>" alt="Reino Burguer">
            </a>
            <nav class="nav-visitante-inicio" aria-label="Navegação principal">
                <a href="<?= url('index.php') ?>">Início</a>
                <a href="<?= url('app/informacoes.php') ?>">Sobre</a>
            </nav>
        </div>
        <h1 class="titulo-marca">Reino Burguer</h1>
        <nav class="nav-visitante-conta" aria-label="Acesso à conta">
            <a href="<?= url('login/login.php') ?>">Login</a>
            <a href="<?= url('login/cadastrar.php') ?>">Cadastre-se</a>
        </nav>
    </header>
    <?php
}
?>

<?php if (($_GET['carrinho'] ?? '') === 'ok'): ?>
    <div class="alert alert-success" role="status">Produto adicionado ao carrinho.</div>
<?php elseif (($_GET['carrinho'] ?? '') === 'erro'): ?>
    <div class="alert alert-danger" role="alert">Não foi possível adicionar o produto.</div>
<?php endif; ?>

<?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] === 'admin'): ?>
    <?php $dashboard = obter_dashboard_admin($conexao); ?>
    <section class="dashboard-admin" aria-label="Dashboard administrativo">
        <h2 class="dashboard-title">Dashboard</h2>
        <div class="dashboard-grid">
            <article class="stat-card stat-card--accent">
                <span class="stat-label">Renda mensal</span>
                <span class="stat-value">R$ <?= number_format((float) $dashboard['renda_mensal'], 2, ',', '.') ?></span>
            </article>
            <article class="stat-card stat-card--secondary">
                <span class="stat-label">Pedidos este mês</span>
                <span class="stat-value"><?= (int) $dashboard['pedidos_mes'] ?></span>
            </article>
            <article class="stat-card stat-card--info">
                <span class="stat-label">Clientes</span>
                <span class="stat-value"><?= (int) $dashboard['clientes'] ?></span>
            </article>
        </div>
    </section>
<?php endif; ?>

<hr>
   <section class="painel-filtros">
    <form action="<?= url('index.php') ?>" method="GET" class="form-filtros">
        
        <input type="text" name="busca" placeholder="Buscar item do cardápio..." value="<?= htmlspecialchars($busca) ?>">

        
        <select name="categoria">
            <option value="">Todas as Categorias</option>
            <option value="hamburgueres" <?= $categoria === 'hamburgueres' ? 'selected' : '' ?>>Hambúrgueres</option>
            <option value="hot-dogs" <?= $categoria === 'hot-dogs' ? 'selected' : '' ?>>Hot dogs</option>
            <option value="lanches" <?= $categoria === 'lanches' ? 'selected' : '' ?>>Lanches prensados</option>
            <option value="combos" <?= $categoria === 'combos' ? 'selected' : '' ?>>Combos</option>
            <option value="acompanhamentos" <?= $categoria === 'acompanhamentos' ? 'selected' : '' ?>>Acompanhamentos</option>
            <option value="bebidas" <?= $categoria === 'bebidas' ? 'selected' : '' ?>>Bebidas</option>
            <option value="molhos" <?= $categoria === 'molhos' ? 'selected' : '' ?>>Molhos</option>
        </select>

        <button type="submit">Procurar</button>
        
        <?php if (!empty($busca) || !empty($categoria) || !empty($ordem)): ?>
            <a href="<?= url('index.php') ?>" class="btn-limpar">Limpar Filtros</a>
        <?php endif; ?>
    </form>
</section>





    <main class="container">
        <h1>Cardápio Reino Burguer</h1>

        <div class="vitrine-produtos">
            <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="card-produto">
                        
                        <div class="detalhes-produto">
                            <span class="categoria-produto"><?= htmlspecialchars(nome_categoria_cardapio($produto['categoria'])) ?></span>
                            <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                            <p class="descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
                            
                            <div class="info-preco">
                                <span class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                            </div>
                        </div>

                        
                        <?php if (($_SESSION['perfil'] ?? '') === 'cliente'): ?>
                            <div class="acoes-produto">
                                <form action="<?= url('mpedidos/acrescentar.php') ?>" method="POST">
                                    <input type="hidden" name="produto_id" value="<?= (int) $produto['id'] ?>">
                                    <button type="submit" class="btn btn-primary"
                                            <?= empty($produto['ativo']) ? 'disabled' : '' ?>>
                                        <?= empty($produto['ativo']) ? 'Indisponível' : '🛒 Adicionar ao carrinho' ?>
                                    </button>
                                </form>
                            </div>
                        <?php elseif (!isset($_SESSION['perfil'])): ?>
                            <div class="acoes-produto">
                                <a class="btn btn-outline" href="<?= url('login/login.php') ?>">Entre para comprar</a>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum produto cadastrado.</p>
            <?php endif; ?>
        </div>
    </main>

 

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
