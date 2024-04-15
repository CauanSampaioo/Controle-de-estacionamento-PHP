-- Atualizando os valores de valor_primeira_hora e valor_hora_adicional na tabela categorias_veiculo
UPDATE categorias_veiculo
SET valor_primeira_hora = CASE
                            WHEN nome_categoria = 'moto' THEN 5
                            WHEN nome_categoria = 'carro' THEN 10
                            WHEN nome_categoria = 'caminhao' THEN 20
                            WHEN nome_categoria = 'microonibus' THEN 20
                            WHEN nome_categoria = 'onibus' THEN 20
                          END,
    valor_hora_adicional = CASE
                            WHEN nome_categoria = 'moto' THEN 2
                            WHEN nome_categoria = 'carro' THEN 3
                            WHEN nome_categoria = 'caminhao' THEN 5
                            WHEN nome_categoria = 'microonibus' THEN 5
                            WHEN nome_categoria = 'onibus' THEN 6
                          END;
