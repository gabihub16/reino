<?php
require_once __DIR__ . '/../login/verficacli.php';
require_once __DIR__ . '/../includes/functions.php';

$itens = listar_carrinho($conexao, (int) $_SESSION['id']);
$total = 0;
foreach ($itens as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - Reino Burguer</title>
    <link rel="stylesheet" href="<?= url('css/style.css') ?>">
</head>
<body>
    <h1>Meu Carrinho</h1>

    <header class="header-publico">
        <a class="header-brand" href="<?= url('index.php') ?>">
            <img src="<?= url('assets/reino-burguer-logo.png') ?>" alt="Reino Burguer">
        </a>
        <nav>
            <a href="<?= url('index.php') ?>">Início</a>
            <a href="<?= url('login/logout.php') ?>">Sair (Logout)</a>
        </nav>
    </header>

    <?php if (($_GET['adicionado'] ?? '') === 'ok'): ?>
        <div class="alert alert-success" role="status">Produto adicionado ao carrinho.</div>
    <?php elseif (($_GET['adicionado'] ?? '') === 'erro'): ?>
        <div class="alert alert-danger" role="alert">Não foi possível adicionar o produto. Ele pode estar inativo.</div>
    <?php endif; ?>

    <?php if (empty($itens)): ?>
        <div class="alert alert-info" role="status">Seu carrinho está vazio.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="num">Preço</th>
                        <th class="num">Qtd.</th>
                        <th class="num">Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($item['nome']) ?></strong><br>
                            <small><?= htmlspecialchars($item['marca']) ?></small>
                        </td>
                        <td class="num">R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td class="num"><?= (int) $item['quantidade'] ?></td>
                        <td class="num">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                        <td>
                            <form action="<?= url('mpedidos/excluir.php') ?>" method="POST">
                                <input type="hidden" name="produto_id" value="<?= (int) $item['produto_id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p><strong>Total: R$ <?= number_format($total, 2, ',', '.') ?></strong></p>
    <?php endif; ?>

    <section class="carrinho-cardapio-link" aria-label="Continuar comprando">
        <a class="btn btn-primary" href="<?= url('index.php') ?>">Ir para o cardápio</a>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
