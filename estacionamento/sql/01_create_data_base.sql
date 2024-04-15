-- Criação do banco de dados estacionamento_wi5
--CREATE DATABASE estacionamento_wi5;

-- Conexão com o banco de dados estacionamento_wi5
--\c estacionamento_wi5

-- Criação da tabela categorias_veiculo
CREATE TABLE categorias_veiculo (
    id_categoria SERIAL PRIMARY KEY,
    nome_categoria VARCHAR(100),
    habilitado BOOLEAN
);

-- Criação da tabela veiculo
CREATE TABLE veiculo (
    id_veiculo SERIAL PRIMARY KEY,
    id_categoria INT REFERENCES categorias_veiculo(id_categoria),
    placa_veiculo VARCHAR(20) NOT NULL,
    descricao TEXT,
    CONSTRAINT fk_categoria FOREIGN KEY (id_categoria) REFERENCES categorias_veiculo(id_categoria)
);

-- Criação da tabela movimento_veiculo
CREATE TABLE movimento_veiculo (
    id_movimento SERIAL PRIMARY KEY,
    tipo_movimento VARCHAR(7) CHECK (tipo_movimento IN ('entrada', 'saida')),
    id_veiculo INT REFERENCES veiculo(id_veiculo),
    data_movimento TIMESTAMP,
    valor_cobrado NUMERIC DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_veiculo FOREIGN KEY (id_veiculo) REFERENCES veiculo(id_veiculo)
);

-- Criação da tabela tarifas
CREATE TABLE tarifas (
    id_tarifa SERIAL PRIMARY KEY,
    nome VARCHAR(100),
    descricao TEXT
);
