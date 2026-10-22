<!-- Cabeçalho administrativo compartilhado, incluído nas páginas de administração. -->
<header>
    <!-- Logo da hamburgueria: ao clicar, retorna à página inicial. -->
        <a class="header-brand" href="<?= url('index.php') ?>">
            <img src="<?= url('assets/reino-burguer-logo.png') ?>" alt="Reino Burguer">
        </a>
    <!-- Menu com atalhos para gerenciar o cardápio e encerrar a sessão. -->
        <nav class="nav-links">
            <a href="<?= url('index.php') ?>">Início</a>
            <a href="<?= url('app/create.php') ?>">Cadastrar Produto</a>
            <a href="<?= url('app/delete.php') ?>">Excluir Produto</a>
            <a href="<?= url('app/select.php') ?>">Cardápio</a>
            <a href="<?= url('app/select_where.php') ?>">Consultar Produto</a>
            <a href="<?= url('app/update.php') ?>">Atualizar Produto</a>
            <a href="<?= url('login/logout.php') ?>">Logout</a>
        </nav>
</header>
