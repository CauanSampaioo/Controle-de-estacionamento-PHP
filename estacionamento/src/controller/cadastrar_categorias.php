<?php
global $pdo;
// HTTP POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se todos os campos necessários foram enviados via POST
    if (
        isset(
            $_POST["nome_categoria"],
            //$_POST["habilitado"],
            $_POST["valor_primeira_hora"],
            $_POST["valor_hora_adicional"]
        )
    ) {
        try {
             // Dados recebidos via $_POST
            $nomeCategoria = $_POST['nome_categoria'];
            //$habilitado = $_POST['habilitado'];
            $habilitado = 1;
            $valorPrimeiraHora = $_POST['valor_primeira_hora'];
            $valorHoraAdicional = $_POST['valor_hora_adicional'];
            $dadosCompletos = true;

            // Preparando a consulta SQL para inserir uma nova categoria de veículo
            $sql = "INSERT INTO categorias_veiculo (nome_categoria, habilitado, valor_primeira_hora, valor_hora_adicional)
            VALUES (:nome_categoria, :habilitado, :valor_primeira_hora, :valor_hora_adicional)";
            $stmt = $pdo->prepare($sql);

            // Bind dos parâmetros
            $stmt->bindParam(":nome_categoria", $nomeCategoria);
            $stmt->bindParam(":habilitado", $habilitado);
            $stmt->bindParam(":valor_primeira_hora", $valorPrimeiraHora);
            $stmt->bindParam(":valor_hora_adicional", $valorHoraAdicional);

            // Executando a consulta
            $stmt->execute();

            // Criado com sucesso
            http_response_code(201);
            echo "Categoria de veículo cadastrada com sucesso!";
        } catch (PDOException $e) {
            // Erro na aplicação
            http_response_code(500);

            // Em caso de erro, exibe a mensagem de erro
            echo "Erro ao cadastrar categoria de veículo: " . $e->getMessage();
        }
    } else {
      // Erro causado pelo usuário
      http_response_code(403);
      // Se algum campo estiver faltando, exibe uma mensagem de erro
      echo "Todos os campos são obrigatórios!";
    }
} else {
    // Erro causado pelo usuário
    http_response_code(405);
    exit("Método Não Permitido\n");
}
