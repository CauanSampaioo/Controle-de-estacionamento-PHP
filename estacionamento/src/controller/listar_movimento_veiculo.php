<?php

global $pdo;

try {
    // Criando uma conexão PDO com PostgreSQL
    //$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

    // HTTP GET request
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        // Preparando e executando a consulta SQL
        $stmt = $pdo->query("
          SELECT
              v.id_veiculo,
              c.nome_categoria,
              v.placa_veiculo,
              v.descricao,
              c.valor_primeira_hora,
              c.valor_hora_adicional,
              m.valor_cobrado,
              m.tipo_movimento,
              m.data_movimento
          FROM
              veiculo v
              NATURAL JOIN categorias_veiculo c
              NATURAL JOIN movimento_veiculo m
        ");

        // Convertendo os resultados para JSON
        $movimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Definindo o tipo de conteúdo da resposta como JSON
        header("Content-Type: application/json");

        // Exibindo os resultados como JSON
        http_response_code(200);
        echo json_encode($movimentos);
    } else {
        // Erro causado pelo usuário
        http_response_code(405);
        exit("Método Não Permitido\n");
    }
} catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem de erro
    http_response_code(500);
    echo json_encode(["error" => "Erro ao listar movimentos: " . $e->getMessage()]);
}
