<?php

global $pdo;

// HTTP GET request
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Criando uma conexão PDO com PostgreSQL
        //$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

        // Preparando e executando a consulta SQL
        $stmt = $pdo->query("SELECT * FROM veiculo");

        // Convertendo os resultados para JSON
        $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Definindo o tipo de conteúdo da resposta como JSON
        header("Content-Type: application/json");

        // Exibindo os resultados como JSON
        http_response_code(200);
        echo json_encode($veiculos);
    } catch (PDOException $e) {
        // Em caso de erro, exibe a mensagem de erro
        http_response_code(500);
        echo json_encode(["error" => "Erro ao listar veículos: " . $e->getMessage()]);
    }
} else {
    // Erro causado pelo usuário
    http_response_code(405);
    exit("Método Não Permitido\n");
}
