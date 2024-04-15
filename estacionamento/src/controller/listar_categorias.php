<?php
global $pdo;

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    try {
        // Preparando e executando a consulta SQL
        $stmt = $pdo->query("SELECT * FROM categorias_veiculo");

        // Convertendo os resultados para JSON
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Definindo o tipo de conteúdo da resposta como JSON
        header("Content-Type: application/json");

        http_response_code(200);
        // Exibindo os resultados como JSON
        echo json_encode($categorias);
    } catch (PDOException $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo json_encode([
            "error" =>
                "Erro ao listar categorias de veículo: " . $e->getMessage(),
        ]);
    }
} else {
    // Erro causado pelo usuário
    http_response_code(405);
    exit("Método Não Permitido\n");
}
