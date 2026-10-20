# Reino Burguer

## Sobre o projeto

O Reino Burguer é um sistema web para apresentar e administrar o cardápio de uma hamburgueria cristã. Visitantes podem consultar e pesquisar produtos; clientes podem criar uma conta e usar o carrinho; administradores podem gerenciar os produtos e consultar indicadores.

## Tecnologias

- PHP para a lógica do servidor.
- PostgreSQL para armazenar os dados.
- PDO para a conexão e execução de consultas.
- HTML e CSS para a interface responsiva.

## Funcionalidades

### Cardápio

- Exibe hambúrgueres, hot dogs, lanches, combos, acompanhamentos, bebidas e molhos.
- Permite procurar produtos pelo nome ou descrição e filtrar por categoria.

### Clientes e carrinho

- Cadastro, login e logout de clientes.
- Inclusão de produtos disponíveis no carrinho.
- Consulta de quantidades, preços e total; remoção de itens.
- As senhas de novos cadastros são armazenadas com hash.

### Administração

- Cadastro, consulta, listagem, atualização e exclusão de produtos.
- Controle de disponibilidade de cada produto.
- Dashboard com quantidade de clientes, pedidos do mês e renda mensal.

> Os indicadores de renda e pedidos dependem de dados na tabela `pedidos`. O sistema possui a estrutura do banco, mas não registra pedidos por meio de um fluxo de finalização de compra.

## Banco de dados

O arquivo `database/cardapio.sql` prepara as tabelas:

- `produtos`: dados, preços e disponibilidade do cardápio.
- `clientes`: dados de acesso, contato e perfil.
- `carrinho`: produtos e quantidades de cada cliente.
- `pedidos` e `pedido_itens`: estrutura para pedidos e itens.

## Como executar

1. Configure a conexão com o PostgreSQL pelas variáveis de ambiente `REINO_DB_HOST`, `REINO_DB_NAME`, `REINO_DB_USER` e `REINO_DB_PASS`, ou ajuste os valores padrão em `database/connect.php`.
2. Execute `database/cardapio.sql` no banco PostgreSQL para criar as tabelas e inserir os produtos iniciais.
3. Inicie o projeto em um servidor com PHP e acesse `index.php` pelo navegador.

## Acesso administrativo

- E-mail informado: `jorgeamd@gmail`
- A senha deve ser configurada localmente e compartilhada somente por um canal seguro. Não a armazene em arquivos versionados.

O login valida senhas com `password_verify()`. Para definir ou alterar uma senha, gere o hash em PHP com `password_hash()` e atualize apenas a conta correta, identificada por um ID ou e-mail confirmado. Nunca grave a senha em texto puro no banco.

## Organização das pastas

- `index.php`: página inicial, cardápio e dashboard administrativo.
- `app/`: páginas administrativas e página Sobre.
- `login/`: cadastro, login, logout e verificações de acesso.
- `mpedidos/`: página e ações do carrinho.
- `includes/`: configurações e funções compartilhadas.
- `database/`: conexão e scripts SQL.
- `css/` e `assets/`: estilos e recursos visuais.
