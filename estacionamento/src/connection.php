<?php
global $pdo;

// Configurações do banco de dados
$host = 'localhost'; // Host do banco de dados
$dbname = 'estacionamento'; // Nome do banco de dados
$username = 'postgres'; // Nome de usuário do banco de dados
$password = 'root'; // Senha do banco de dados
$port = '5432'; // Porta padrão do PostgreSQL

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$username;password=$password";
try {
    // Criando uma conexão PDO com PostgreSQL
    $pdo = new PDO($dsn);

    // Configurando o PDO para lançar exceções em caso de erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "Conexão bem sucedida!";
} catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem de erro
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
