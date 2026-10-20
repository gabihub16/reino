# Apresentação do projeto Reino Burguer

## Resumo

O Reino Burguer é um sistema web para apresentar e administrar o cardápio de uma hamburgueria cristã. A página inicial exibe os produtos em cartões e permite procurar por nome ou descrição e filtrar por categoria. Clientes podem criar uma conta, entrar no sistema e gerenciar os produtos do próprio carrinho. Administradores podem acompanhar indicadores e manter o cardápio atualizado.

## Tecnologias

- **PHP** para a lógica do servidor e a geração das páginas.
- **PostgreSQL** para armazenar produtos, clientes e dados do carrinho.
- **PDO** para conectar ao banco e executar consultas parametrizadas.
- **HTML e CSS** para estruturar e apresentar a interface responsiva.

## Funcionalidades

### Cardápio e página Sobre

O cardápio apresenta hambúrgueres, hot dogs, lanches, combos, acompanhamentos, bebidas e molhos. É possível buscar produtos por nome ou descrição e filtrar por categoria. A página “Sobre” apresenta a identidade cristã da hamburgueria e oferece um botão para acessar o cardápio.

### Clientes e carrinho

O cliente pode se cadastrar, entrar e adicionar produtos disponíveis ao carrinho. No carrinho, pode consultar quantidades, preços e total, remover itens e usar um botão para voltar ao cardápio. O acesso às páginas do carrinho é restrito a clientes autenticados. As senhas de novos cadastros são armazenadas com hash.

### Administração

As páginas administrativas permitem cadastrar, consultar, listar, atualizar e excluir produtos, além de alterar sua disponibilidade. O administrador também vê um dashboard com renda mensal, pedidos do mês e quantidade de clientes.

Os indicadores de renda e pedidos dependem dos registros na tabela `pedidos`; o sistema define a estrutura dessa tabela, mas não possui um fluxo de finalização de compra que registre pedidos. Por isso, esses indicadores só refletem pedidos inseridos no banco por outro processo. A contagem de clientes considera contas com perfil de cliente.

## Banco de dados

O arquivo [`database/cardapio.sql`](./database/cardapio.sql) contém a estrutura inicial do banco e o cardápio padrão:

- **`produtos`**: nome, marca, categoria, descrição, preço e disponibilidade.
- **`clientes`**: dados da conta, e-mail único, senha e perfil.
- **`carrinho`**: produtos e quantidades associados a cada cliente.
- **`pedidos`** e **`pedido_itens`**: estrutura para registrar pedidos e seus itens, usada nos indicadores do dashboard.

O script de carga inicial evita inserir novamente produtos que já existem na mesma categoria.

## Organização do projeto

- **`index.php`**: página inicial, dashboard do administrador e cardápio.
- **`app/`**: páginas administrativas e página Sobre.
- **`login/`**: cadastro, login, logout e verificações de acesso.
- **`mpedidos/`**: páginas e ações do carrinho.
- **`includes/functions.php`**: funções reutilizadas para produtos, clientes, filtros, dashboard e carrinho.
- **`database/`**: conexão com PostgreSQL e script SQL.
- **`css/` e `assets/`**: estilos, logo e outros recursos visuais.

## Roteiro breve para apresentação

> Este é o Reino Burguer, um sistema web para o cardápio de uma hamburgueria cristã. O projeto utiliza PHP, PostgreSQL, PDO, HTML e CSS. Na página inicial, o visitante pode explorar os produtos, procurar por nome ou descrição e filtrar por categoria. Clientes cadastrados podem adicionar produtos disponíveis ao carrinho, remover itens e consultar o total. O administrador pode gerenciar os produtos e acompanhar um dashboard com a quantidade de clientes e indicadores de pedidos e renda mensal. O sistema também conta com uma página Sobre que apresenta a identidade da hamburgueria.

## Como preparar o projeto

1. Configure o acesso ao PostgreSQL pelas variáveis de ambiente `REINO_DB_HOST`, `REINO_DB_NAME`, `REINO_DB_USER` e `REINO_DB_PASS`, ou ajuste os valores padrão em [`database/connect.php`](./database/connect.php).
2. Execute [`database/cardapio.sql`](./database/cardapio.sql) no banco PostgreSQL para criar as tabelas e carregar os produtos iniciais.
3. Inicie o projeto em um servidor com PHP e acesse `index.php` pelo navegador.
