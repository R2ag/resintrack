-- ============================================
-- BANCO DE DADOS - RESINTRACK
-- ============================================

DROP DATABASE IF EXISTS controle_resinas;
CREATE DATABASE controle_resinas 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE controle_resinas;

-- ============================================
-- TABELA: PERFIS
-- ============================================

CREATE TABLE perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

INSERT INTO perfis (nome) VALUES 
('Administrador'),
('Supervisor'),
('Operador');

-- ============================================
-- TABELA: USUARIOS
-- ============================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil_id INT NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_usuario_perfil
        FOREIGN KEY (perfil_id) REFERENCES perfis(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_usuario_email ON usuarios(email);

-- ============================================
-- TABELA: INSUMOS
-- ============================================

CREATE TABLE insumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(150) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABELA: LOTES
-- ============================================

CREATE TABLE lotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote VARCHAR(100) NOT NULL,
    insumo_id INT NOT NULL,
    peso_inicial DECIMAL(12,3) NOT NULL,
    tara DECIMAL(12,3) NOT NULL,
    saldo_atual DECIMAL(12,3) NOT NULL DEFAULT 0,
    data_entrada DATE NOT NULL,
    data_fim DATE NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_lote_insumo
        FOREIGN KEY (insumo_id) REFERENCES insumos(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_lote_insumo ON lotes(insumo_id);
CREATE INDEX idx_lote_status ON lotes(data_fim);

-- ============================================
-- TABELA: LOTES_ENTRADAS (ENTRADAS PARCELADAS)
-- ============================================

CREATE TABLE lotes_entradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT NOT NULL,
    quantidade DECIMAL(12,3) NOT NULL,
    data_entrada DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_lotes_entradas_lote
        FOREIGN KEY (lote_id) REFERENCES lotes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_lotes_entradas_lote ON lotes_entradas(lote_id);
CREATE INDEX idx_lotes_entradas_data ON lotes_entradas(data_entrada);

-- ============================================
-- TABELA: PESAGENS
-- ============================================

CREATE TABLE pesagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT NOT NULL,
    data_pesagem DATE NOT NULL,
    peso_apurado DECIMAL(12,3) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_pesagem_lote
        FOREIGN KEY (lote_id) REFERENCES lotes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_pesagem_lote ON pesagens(lote_id);
CREATE INDEX idx_pesagem_data ON pesagens(data_pesagem);

-- ============================================
-- TABELA: PROCESSOS
-- ============================================

CREATE TABLE processos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_processo_codigo ON processos(codigo);

-- ============================================
-- TABELA: MATERIAIS
-- ============================================

CREATE TABLE materiais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL UNIQUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABELA: BLOCOS
-- ============================================

CREATE TABLE blocos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    material_id INT NOT NULL,
    numero_chapas INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_bloco_material
        FOREIGN KEY (material_id) REFERENCES materiais(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_bloco_codigo ON blocos(codigo);
CREATE INDEX idx_bloco_material ON blocos(material_id);

-- ============================================
-- TABELA: EXECUCOES_PROCESSOS
-- ============================================

CREATE TABLE execucoes_processos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    processo_id INT NOT NULL,
    bloco_id INT NOT NULL,
    data_execucao DATE NOT NULL,
    qtd_chapas INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_execucao_processo
        FOREIGN KEY (processo_id) REFERENCES processos(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_execucao_bloco
        FOREIGN KEY (bloco_id) REFERENCES blocos(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE INDEX idx_execucao_processo ON execucoes_processos(processo_id);
CREATE INDEX idx_execucao_bloco ON execucoes_processos(bloco_id);
CREATE INDEX idx_execucao_data ON execucoes_processos(data_execucao);

-- ============================================
-- TABELA: EXECUCOES_INSUMOS
-- ============================================

CREATE TABLE execucoes_insumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    execucao_id INT NOT NULL,
    lote_id INT NOT NULL,
    quantidade_por_chapa DECIMAL(12,3) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_execucao_insumo_execucao
        FOREIGN KEY (execucao_id) REFERENCES execucoes_processos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_execucao_insumo_lote
        FOREIGN KEY (lote_id) REFERENCES lotes(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE INDEX idx_execucao_insumo_execucao ON execucoes_insumos(execucao_id);
CREATE INDEX idx_execucao_insumo_lote ON execucoes_insumos(lote_id);

-- ============================================
-- USUARIO ADMIN INICIAL
-- Senha padrão: admin123
-- (hash gerado com password_hash do PHP)
-- ============================================

INSERT INTO usuarios (nome, email, senha, perfil_id)
VALUES (
    'Administrador',
    'admin@resintrack.com',
    '$2y$10$Y3T0OwADfJOGT8.NWXnU2.F4t9PZn2pmxAaI0//JAqGDvptvlbj8.',
    1
);

-- ============================================
-- FIM DO SCRIPT
-- ============================================

