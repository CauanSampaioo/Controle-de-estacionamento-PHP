<?php
global $pdo;

// HTTP POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se todos os campos necessários foram enviados via POST
    if (
        isset(
            $_POST["id_categoria"],
            $_POST["placa_veiculo"],
            $_POST["descricao"]
        )
    ) {
        try {
            // Dados recebidos via $_POST
            $idCategoria = $_POST['id_categoria'];
            $placaVeiculo = $_POST['placa_veiculo'];
            $descricao = $_POST['descricao'];

            // Criando uma conexão PDO com PostgreSQL
            //$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

            // Preparando a consulta SQL para inserir um novo veículo
            $sql = "INSERT INTO veiculo (id_categoria, placa_veiculo, descricao)
                    VALUES (:id_categoria, :placa_veiculo, :descricao)";
            $stmt = $pdo->prepare($sql);

            // Bind dos parâmetros
            $stmt->bindParam(":id_categoria", $idCategoria);
            $stmt->bindParam(":placa_veiculo", $placaVeiculo);
            $stmt->bindParam(":descricao", $descricao);

            // Executando a consulta
            $stmt->execute();

            // Criado com sucesso
            http_response_code(201);
            echo "Veículo cadastrado com sucesso!";
        } catch (PDOException $e) {
            // Erro na aplicação
            http_response_code(500);

            // Em caso de erro, exibe a mensagem de erro
            echo "Erro ao cadastrar veículo: " . $e->getMessage();
        }
    } else {
        // Erro causado pelo usuário
        http_response_code(400);
        // Se algum campo estiver faltando, exibe uma mensagem de erro
        echo "Todos os campos são obrigatórios!";
    }
} else {
    // Erro causado pelo usuário
    http_response_code(405);
    exit("Método Não Permitido\n");
}
