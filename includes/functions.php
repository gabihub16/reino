<?php
require_once __DIR__ . '/../database/connect.php';
require_once __DIR__ . '/configuracao.php';

function cadastrar_produto($conexao, $nome, $marca, $categoria, $descricao, $preco, $ativo) {
    $categorias_validas = ['hamburgueres', 'hot-dogs', 'lanches', 'combos', 'acompanhamentos', 'bebidas', 'molhos'];
    if (!in_array($categoria, $categorias_validas, true)) {
        echo "<script>alert('Selecione uma categoria válida.');</script>";
        return;
    }
    $ativo = filter_var($ativo, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if ($ativo === null) {
        echo "<script>alert('Selecione a disponibilidade do produto.');</script>";
        return;
    }

    $sql = "INSERT INTO produtos (nome, marca, categoria, descricao, preco, ativo, imagem)
            VALUES (:nome, :marca, :categoria, :descricao, :preco, :ativo, :imagem)";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':marca' => $marca,
        ':categoria' => $categoria,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':ativo' => $ativo ? 'true' : 'false',
        ':imagem' => null,
    ]);

    echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='" . url('index.php') . "';</script>";
}
function deletar_produto($conexao, $id) 
{
    $id = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        echo "Informe um ID de produto válido.";
        return;
    }

    $stmt = $conexao->prepare("DELETE FROM produtos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo $stmt->rowCount() > 0
        ? "Produto {$id} excluído."
        : "Produto não encontrado.";
}

function listar_produtos($conexao)
{
    $sql = "SELECT * FROM produtos ORDER BY id";

    $stmt = $conexao->prepare($sql);
    $stmt->execute();

    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $escape = static fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    echo '<div class="vitrine-produtos">';
    if (!$produtos) {
        echo '<p>Nenhum produto cadastrado.</p>';
        echo '</div>';
        return;
    }

    foreach ($produtos as $produto) {
        echo '<article class="card-produto">';
        echo '<div class="detalhes-produto">';
        echo '<span class="categoria-produto">' . $escape(nome_categoria_cardapio($produto['categoria'])) . '</span>';
        echo '<h3>' . $escape($produto['nome']) . '</h3>';
        echo '<p class="descricao">' . $escape($produto['descricao']) . '</p>';
        echo '<div class="info-preco"><span class="preco">R$ ' . number_format((float) $produto['preco'], 2, ',', '.') . '</span></div>';
        echo '<div class="catalogo-admin-meta">';
        echo '<span>ID: ' . (int) $produto['id'] . '</span>';
        echo '<span>Marca: ' . $escape($produto['marca']) . '</span>';
        echo '<span>Situação: ' . (!empty($produto['ativo']) ? 'Disponível' : 'Inativo') . '</span>';
        echo '</div>';
        echo '</div>';
        echo '</article>';
    }
    echo '</div>';
}

function consultar_produto($conexao, $id)
{

     $sql = "SELECT nome, marca, categoria, descricao, preco, ativo, imagem
            FROM produtos
            WHERE id = :id";

    $stmt = $conexao->prepare($sql);
    $stmt-> bindParam(":id", $id);
    $stmt->execute();

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "Produto não encontrado.";
        return;
    }

    $escape = static fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo "Produto: " . $escape($produto['nome']) . "<br>"
        . "Marca: " . $escape($produto['marca']) . "<br>"
        . "Categoria: " . $escape(nome_categoria_cardapio($produto['categoria'])) . "<br>"
        . "Descrição: " . $escape($produto['descricao']) . "<br>"
        . "Preço: R$ " . $escape($produto['preco']) . "<br>"
        . "Situação: " . (!empty($produto['ativo']) ? 'Disponível' : 'Inativo') . "<br>";
}

function atualizar_produto($conexao, $id, $nome, $marca, $categoria, $descricao, $preco, $ativo)
{
    $ativo = filter_var($ativo, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if ($ativo === null) {
        echo "Selecione a disponibilidade do produto.";
        return;
    }

    $stmt = $conexao->prepare("SELECT id FROM produtos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $atual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$atual) {
        echo "Produto não encontrado.";
        return;
    }

    $sql = "UPDATE produtos
            SET nome = :nome, marca = :marca, categoria = :categoria, descricao = :descricao,
                preco = :preco, ativo = :ativo
            WHERE id = :id";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ':nome'      => $nome,
        ':marca'     => $marca,
        ':categoria' => $categoria,
        ':descricao' => $descricao,
        ':preco'     => $preco,
        ':ativo'     => $ativo ? 'true' : 'false',
        ':id'        => $id
    ]);

    echo "Produto atualizado com sucesso!";
}

// Funções para sistema de login
function cadastrar_cliente($conexao, $nome, $email, $senha, $telefone)
{
    $sql = "INSERT INTO clientes (nome, email, senha, telefone, perfil)
            VALUES (:nome, :email, :senha, :telefone, 'cliente')";
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':nome', trim($nome));
        $stmt->bindValue(':email', mb_strtolower(trim($email), 'UTF-8'));
        $stmt->bindValue(':senha', $senha_hash);
        $stmt->bindValue(':telefone', trim($telefone));
        $stmt->execute();
        return ['sucesso' => true, 'mensagem' => 'Cadastro realizado. Você já pode entrar com seu e-mail e senha.'];
    } catch (PDOException $e) {
        if ($e->getCode() === '23505' || $e->getCode() === '23000') {
            return ['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.'];
        }

        error_log('Falha ao cadastrar cliente: ' . $e->getMessage());
        return ['sucesso' => false, 'mensagem' => 'Não foi possível concluir o cadastro. Verifique os dados e tente novamente.'];
    }
}

function consulta_cliente($conexao, $email)
{
    $sql = "SELECT id, nome, email, senha, perfil
            FROM clientes
            WHERE LOWER(email) = LOWER(:email)";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([':email' => trim($email)]);

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    return $cliente;
}

function buscar_produtos_filtrados($conexao, $busca = '', $categoria = '', $ordem = '') {
    $sql = "SELECT * FROM produtos
            WHERE LOWER(categoria) IN ('hamburgueres', 'hot-dogs', 'lanches', 'combos', 'acompanhamentos', 'bebidas', 'molhos')";
    $params = [];

    // Filtro por Nome ou Descrição (insensível a maiúsculas/minúsculas)
    if (!empty($busca)) {
        $sql .= " AND (LOWER(nome) LIKE LOWER(:busca_nome) OR LOWER(descricao) LIKE LOWER(:busca_descricao))";
        $params[':busca_nome'] = '%' . $busca . '%';
        $params[':busca_descricao'] = '%' . $busca . '%';
    }

    // Filtro por Categoria
    if (!empty($categoria) && $categoria !== 'todas') {
        $sql .= " AND LOWER(categoria) = LOWER(:categoria)";
        $params[':categoria'] = strtolower($categoria);
    }

    // Ordenação dos resultados
    switch ($ordem) {
        case 'preco_asc':
            $sql .= " ORDER BY preco ASC";
            break;
        case 'preco_desc':
            $sql .= " ORDER BY preco DESC";
            break;
        default:
            $sql .= " ORDER BY id DESC";
            break;
    }

    $stmt = $conexao->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function nome_categoria_cardapio($categoria)
{
    $categoria = (string) $categoria;
    $categorias = [
        'hamburgueres' => 'Hambúrgueres',
        'hot-dogs' => 'Hot dogs',
        'lanches' => 'Lanches prensados',
        'combos' => 'Combos',
        'acompanhamentos' => 'Acompanhamentos',
        'bebidas' => 'Bebidas',
        'molhos' => 'Molhos'
    ];

    return $categorias[strtolower($categoria)] ?? $categoria;
}

function garantir_tabelas_dashboard_admin($conexao)
{
    $sqls = [
        "CREATE TABLE IF NOT EXISTS pedidos (
            id INTEGER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
            cliente_id INTEGER NOT NULL REFERENCES clientes (id) ON DELETE RESTRICT,
            total NUMERIC(10, 2) NOT NULL CHECK (total >= 0),
            status VARCHAR(20) NOT NULL DEFAULT 'finalizado'
                CHECK (status IN ('pendente', 'finalizado', 'cancelado')),
            criado_em TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS pedido_itens (
            pedido_id INTEGER NOT NULL REFERENCES pedidos (id) ON DELETE CASCADE,
            produto_id INTEGER NOT NULL REFERENCES produtos (id) ON DELETE RESTRICT,
            quantidade INTEGER NOT NULL CHECK (quantidade > 0),
            preco_unitario NUMERIC(10, 2) NOT NULL CHECK (preco_unitario >= 0),
            PRIMARY KEY (pedido_id, produto_id)
        )"
    ];

    foreach ($sqls as $sql) {
        $conexao->exec($sql);
    }
}

function obter_dashboard_admin($conexao)
{
    garantir_tabelas_dashboard_admin($conexao);

    $total_clientes = (int) $conexao->query("SELECT COUNT(*) FROM clientes WHERE perfil = 'cliente'")->fetchColumn();
    $renda_mensal = (float) $conexao->query(
        "SELECT COALESCE(SUM(total), 0)
         FROM pedidos
         WHERE status <> 'cancelado'
           AND criado_em >= date_trunc('month', CURRENT_DATE)"
    )->fetchColumn();
    $pedidos_mes = (int) $conexao->query(
        "SELECT COUNT(*)
         FROM pedidos
         WHERE status <> 'cancelado'
           AND criado_em >= date_trunc('month', CURRENT_DATE)"
    )->fetchColumn();

    return [
        'clientes' => $total_clientes,
        'pedidos_mes' => $pedidos_mes,
        'renda_mensal' => $renda_mensal,
    ];
}


// ===== Carrinho =====

function adicionar_ao_carrinho($conexao, $cliente_id, $produto_id)
{
    // Só adiciona produtos que estejam marcados como disponíveis.
    $stmt = $conexao->prepare("SELECT ativo FROM produtos WHERE id = :p");
    $stmt->execute([':p' => $produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto || !$produto['ativo']) {
        return false;
    }

    $sql = "INSERT INTO carrinho (cliente_id, produto_id, quantidade)
            VALUES (:c, :p, 1)
            ON CONFLICT (cliente_id, produto_id)
            DO UPDATE SET quantidade = carrinho.quantidade + 1";

    $stmt = $conexao->prepare($sql);
    return $stmt->execute([
        ':c'       => $cliente_id,
        ':p'       => $produto_id
    ]);
}

function listar_carrinho($conexao, $cliente_id)
{
    $sql = "SELECT c.produto_id, c.quantidade, p.nome, p.marca, p.preco, p.imagem, p.ativo
            FROM carrinho c
            JOIN produtos p ON p.id = c.produto_id
            WHERE c.cliente_id = :c
            ORDER BY c.adicionado_em DESC";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([':c' => $cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function remover_do_carrinho($conexao, $cliente_id, $produto_id)
{
    $stmt = $conexao->prepare("DELETE FROM carrinho WHERE cliente_id = :c AND produto_id = :p");
    return $stmt->execute([':c' => $cliente_id, ':p' => $produto_id]);
}

function contar_itens_carrinho($conexao, $cliente_id)
{
    $stmt = $conexao->prepare("SELECT COALESCE(SUM(quantidade), 0) FROM carrinho WHERE cliente_id = :c");
    $stmt->execute([':c' => $cliente_id]);
    return (int) $stmt->fetchColumn();
}
