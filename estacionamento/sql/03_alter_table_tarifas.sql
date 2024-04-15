-- Adicionando o campo categoria_veiculo na tabela tarifas como chave estrangeira
ALTER TABLE tarifas
ADD COLUMN categoria_veiculo INT,
ADD CONSTRAINT fk_categoria_veiculo FOREIGN KEY (categoria_veiculo) REFERENCES categorias_veiculo(id_categoria);
