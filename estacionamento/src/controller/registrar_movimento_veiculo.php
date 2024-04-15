<?php
global $pdo;

// HTTP POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se todos os campos necessários foram enviados via POST
    if (
        isset(
            $_POST["tipo_movimento"],
            $_POST["id_veiculo"]
        )
    ) {
        try {
            // Dados recebidos via $_POST
            $tipoMovimento = $_POST['tipo_movimeno'];
            $idVeiculo = $_POST['id_veiculo'];t
            //$dataMovimento = $_POST['data_movimento'];
            //$valorCobrado = isset($_POST['valor_cobrado']) ? $_POST['valor_cobrado'] : 0;

            // Criando uma conexão PDO com PostgreSQL
            //$pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);

            $stmt = $pdo->prepare("SELECT tipo_movimento FROM movimento_veiculo
                    WHERE id_veiculo = :id_veiculo ORDER BY data_criacao DESC LIMIT 1");
            $stmt->bindParam(':id_veiculo', $idVeiculo);
            $stmt->execute();
            $ultimaMovimentacao = $stmt->fetch(PDO::FETCH_ASSOC);

            /* *
             * Verificando se o tipo de movimento é 'saida' e
             * se a última movimentação do veículo foi uma 'entrada'
             * */
            if ($tipoMovimento === 'saida') {
                if (
                    ($ultimaMovimentacao && $ultimaMovimentacao['tipo_movimento'] !== 'entrada') ||
                    $ultimaMovimentacao === false
                ) {
                    // Se a última movimentação não foi uma entrada, retorna um erro
                    http_response_code(400);
                    echo "A última movimentação do veículo deve ser uma entrada antes de registrar uma saída.";
                    exit();
                }
            }

            if ($tipoMovimento === 'entrada') {
                if (
                    ($ultimaMovimentacao && $ultimaMovimentacao['tipo_movimento'] !== 'saida')
                ) {
                    // Se a última movimentação não foi uma entrada, retorna um erro
                    http_response_code(400);
                    echo "A última movimentação do veículo deve ser uma saída antes de registrar uma entrada.";
                    exit();
                }
            }

            // Preparando a consulta SQL para inserir um novo movimento de veículo
            $sql = "INSERT INTO movimento_veiculo (
                    tipo_movimento,
                    id_veiculo,
                    data_movimento
                )
                    VALUES (
                        :tipo_movimento,
                        :id_veiculo,
                        NOW()
                    )";
            $stmt = $pdo->prepare($sql);

            // Bind dos parâmetros
            $stmt->bindParam(":tipo_movimento", $tipoMovimento);
            $stmt->bindParam(":id_veiculo", $idVeiculo);
            //$stmt->bindParam(":data_movimento", $dataMovimento);
            //$stmt->bindParam(":valor_cobrado", $valorCobrado);

            // Executando a consulta
            $stmt->execute();

            // Criado com sucesso
            http_response_code(201);

            /**
             * Se for a saída já calcula o valor da cobrança
             */
            if ($tipoMovimento === 'saida') {
                $sql = "SELECT
                            id_veiculo,
                            CEIL(EXTRACT(EPOCH FROM (data_saida - data_entrada)) / 3600) AS horas_passadas,
                            CASE
                                WHEN CEIL(EXTRACT(EPOCH FROM (data_saida - data_entrada)) / 3600) = 1 THEN valor_primeira_hora
                                ELSE valor_primeira_hora + (valor_hora_adicional * (CEIL(EXTRACT(EPOCH FROM (data_saida - data_entrada)) / 3600) - 1))
                            END AS valor_cobrado,
                            nome_categoria,
                            valor_primeira_hora,
                            valor_hora_adicional
                        FROM (
                            SELECT
                                m.id_veiculo,
                                MAX(CASE WHEN m.tipo_movimento = 'entrada' THEN data_movimento END) AS data_entrada,
                                MAX(CASE WHEN m.tipo_movimento = 'saida' THEN data_movimento END) AS data_saida,
                                c.valor_primeira_hora,
                                c.valor_hora_adicional,
                                c.nome_categoria
                            FROM movimento_veiculo m
                            JOIN veiculo v ON m.id_veiculo = v.id_veiculo
                            JOIN categorias_veiculo c ON v.id_categoria = c.id_categoria
                            WHERE m.id_veiculo = :id_veiculo
                            GROUP BY m.id_veiculo, c.id_categoria
                        ) AS ultimas_movimentacoes
                ";

                // Preparando e executando a consulta SQL
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_veiculo', $idVeiculo);
                $stmt->execute();

                // Extraindo os resultados
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                /**
                 * Atualiza o valor cobrado antes de exibir o resultado
                 */
                foreach ($resultados as $resultado) {
                    $id_veiculo = $resultado['id_veiculo'];
                    $valor_cobrado = $resultado['valor_cobrado'];

                    // Atualiza só a última saída
                    $sqlUpdate = "UPDATE movimento_veiculo
                                SET valor_cobrado = :valor_cobrado
                                WHERE id_movimento = (
                                    SELECT id_movimento
                                    FROM movimento_veiculo
                                    WHERE tipo_movimento = 'saida'
                                    AND id_veiculo = :id_veiculo
                                    ORDER BY data_criacao DESC
                                    LIMIT 1
                                )
                    ";
                    $stmtUpdate = $pdo->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':valor_cobrado', $valor_cobrado);
                    $stmtUpdate->bindParam(':id_veiculo', $id_veiculo);
                    $stmtUpdate->execute();
                }

                // Retornando os resultados em JSON
                header('Content-Type: application/json');
                echo json_encode($resultados);
            } else {
            echo "Movimento de Entrada de Veículo cadastrado com sucesso!";
            }
        } catch (PDOException $e) {
            // Erro na aplicação
            http_response_code(500);

            // Em caso de erro, exibe a mensagem de erro
            echo "Erro ao cadastrar movimento de veículo: " . $e->getMessage();
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
?>
