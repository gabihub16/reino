<?php 
$host = getenv('REINO_DB_HOST') ?: '192.168.10.22';
$dbname = getenv('REINO_DB_NAME') ?: 'reino';
$user = getenv('REINO_DB_USER') ?: 'reino';
$pass = getenv('REINO_DB_PASS') ?: 'reino';
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
