<?php
// Abre a conexão PDO com o PostgreSQL usando as configurações do banco do projeto.
$host = 'SEU_LOCALHOST'; // Substitua pelo endereço do seu servidor PostgreSQL
$dbname = 'NOME_DO_BANCO_DE_DADOS'; // Substitua pelo nome do seu banco de dados
$user = 'NOME_DO_USUARIO'; // Substitua pelo nome do usuário do banco de dados;
$pass = 'NOME_DA_SENHA'; // Substitua pela senha do usuário do banco de dados
try {
    $conexao = new PDO(
        "pgsql:host=$host;dbname=$dbname;connect_timeout=5",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    error_log('Falha ao conectar ao banco do Reino Burguer: ' . $e->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados. Verifique a configuração do PostgreSQL.');
}
?>
