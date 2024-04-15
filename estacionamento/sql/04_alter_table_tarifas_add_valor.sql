-- Adicionando o campo 'valor' na tabela 'tarifas' com valor padrão zero
ALTER TABLE tarifas
ADD COLUMN valor NUMERIC DEFAULT 0 NOT NULL;
