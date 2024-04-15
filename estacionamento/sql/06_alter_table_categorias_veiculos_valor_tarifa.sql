-- Adicionando os campos 'valor_primeira_hora' e 'valor_hora_adicional' na tabela 'categorias_veiculo'
ALTER TABLE categorias_veiculo
ADD COLUMN valor_primeira_hora NUMERIC(10, 2) DEFAULT 0.00 NOT NULL,
ADD COLUMN valor_hora_adicional NUMERIC(10, 2) DEFAULT 0.00 NOT NULL;
