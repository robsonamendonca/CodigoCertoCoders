# Tutorial: Estrutura de Banco de Dados SQLite para Comanda Eletrônica

## 📋 Introdução

Este tutorial ensina a criar a estrutura mínima de banco de dados SQLite para o sistema de comanda eletrônica, incluindo DER (Diagrama Entidade-Relacionamento) e scripts SQL.

## 🗺️ DER (Diagrama Entidade-Relacionamento)

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     USUARIOS    │    │      MESAS      │    │    CATEGORIAS   │
├────────────────-┤    ├────────────────-┤    ├────────────────-┤
│ id (PK)         │    │ id (PK)         │    │ id (PK)         │
│ nome            │    │ numero          │    │ nome            │
│ email           │    │ status          │    │ tipo            │
│ senha           │    │ capacidade      │    │ icone           │
│ tipo            │    │ localizacao     │    │ ordem           │
│ pin             │    │ comanda_aberta  │    │                 │
│ ativo           │    │ cliente_nome    │    │                 │
│ created_at      │    │ created_at      │    │                 │
└────────────────-┘    └────────────────-┘    └────────────────-┘
         │                       │                       │
         │                       │                       │
         │              ┌─────────┴─────────┐            │
         │              │                   │            │
         │              ▼                   ▼            │
         │      ┌─────────────────┐  ┌─────────────────┐ │
         │      │    COMANDA      │  │    PRODUTOS     │ │
         │      ├────────────────-┤  ├────────────────-┤ │
         │      │ id (PK)         │  │ id (PK)         │ │
         │      │ mesa_id (FK)    │  │ nome            │ │
         │      │ garcom_id (FK)  │  │ categoria_id(FK)│◄┘
         │      │ cliente_nome    │  │ preco           │
         │      │ status          │  │ descricao       │
         │      │ total           │  │ disponivel      │
         │      │ data_abertura   │  │ local_preparo   │
         │      │ data_fechamento │  │ tempo_preparo   │
         │      │                 │  │ created_at      │
         │      └────────────────-┘  └────────────────-┘
         │              │                       │
         │              │                       │
         │              └─────────┬─────────────┘
         │                        │
         │                        ▼
         │              ┌─────────────────┐
         │              │   COMANDA_ITENS │
         │              ├────────────────-┤
         └─────────────►│ id (PK)         │
                        │ comanda_id (FK) │
                        │ produto_id (FK) │
                        │ garcom_id (FK)  │
                        │ quantidade      │
                        │ preco_unitario  │
                        │ observacoes     │
                        │ status          │
                        │ created_at      │
                        └────────────────-┘
```

## 🏗️ Estrutura do Projeto Database

```
database-comanda/
├── scripts/
│   ├── 01_create_database.sql
│   ├── 02_insert_data.sql
│   └── 03_queries_examples.sql
├── config/
│   └── database.js
└── comanda.db (será gerado automaticamente)
```

## 📝 Scripts SQL

### 1. Script de Criação da Estrutura (01_create_database.sql)

```sql
-- Script: Criação da estrutura do banco de dados Comanda Eletrônica
-- Banco: SQLite
-- Autor: Sistema Comanda Digital

-- Tabela de Categorias de Produtos
CREATE TABLE IF NOT EXISTS categorias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    tipo TEXT NOT NULL CHECK (tipo IN ('comida', 'bebida', 'sobremesa')),
    icone TEXT,
    ordem INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Produtos
CREATE TABLE IF NOT EXISTS produtos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    categoria_id INTEGER NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    descricao TEXT,
    disponivel BOOLEAN DEFAULT 1,
    local_preparo TEXT NOT NULL CHECK (local_preparo IN ('cozinha', 'bar', 'churrasqueira')),
    tempo_preparo INTEGER DEFAULT 10,
    ingredientes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- Tabela de Mesas
CREATE TABLE IF NOT EXISTS mesas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero INTEGER NOT NULL UNIQUE,
    status TEXT NOT NULL DEFAULT 'livre' CHECK (status IN ('livre', 'ocupada', 'reservada', 'indisponivel')),
    capacidade INTEGER NOT NULL DEFAULT 4,
    localizacao TEXT NOT NULL CHECK (localizacao IN ('sala_principal', 'varanda', 'sala_vip', 'terraco')),
    comanda_aberta BOOLEAN DEFAULT 0,
    cliente_nome TEXT,
    reserva_horario TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT UNIQUE,
    senha TEXT NOT NULL,
    tipo TEXT NOT NULL CHECK (tipo IN ('admin', 'garcom', 'cozinha', 'caixa')),
    pin TEXT,
    ativo BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Comandas
CREATE TABLE IF NOT EXISTS comandas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    mesa_id INTEGER NOT NULL,
    garcom_id INTEGER NOT NULL,
    cliente_nome TEXT,
    status TEXT NOT NULL DEFAULT 'aberta' CHECK (status IN ('aberta', 'fechada', 'cancelada')),
    total DECIMAL(10,2) DEFAULT 0.00,
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_fechamento DATETIME,
    observacoes TEXT,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id),
    FOREIGN KEY (garcom_id) REFERENCES usuarios(id)
);

-- Tabela de Itens da Comanda
CREATE TABLE IF NOT EXISTS comanda_itens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    comanda_id INTEGER NOT NULL,
    produto_id INTEGER NOT NULL,
    garcom_id INTEGER NOT NULL,
    quantidade INTEGER NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    observacoes TEXT,
    status TEXT NOT NULL DEFAULT 'pendente' CHECK (status IN ('pendente', 'preparando', 'pronto', 'entregue', 'cancelado')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comanda_id) REFERENCES comandas(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (garcom_id) REFERENCES usuarios(id)
);

-- Tabela de Reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    mesa_id INTEGER NOT NULL,
    cliente_nome TEXT NOT NULL,
    cliente_telefone TEXT,
    data_reserva DATE NOT NULL,
    horario TEXT NOT NULL,
    quantidade_pessoas INTEGER DEFAULT 2,
    status TEXT DEFAULT 'confirmada' CHECK (status IN ('confirmada', 'cancelada', 'finalizada')),
    observacoes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

-- Índices para melhor performance
CREATE INDEX IF NOT EXISTS idx_comandas_mesa_status ON comandas(mesa_id, status);
CREATE INDEX IF NOT EXISTS idx_comanda_itens_status ON comanda_itens(status);
CREATE INDEX IF NOT EXISTS idx_comanda_itens_comanda ON comanda_itens(comanda_id);
CREATE INDEX IF NOT EXISTS idx_produtos_categoria ON produtos(categoria_id);
CREATE INDEX IF NOT EXISTS idx_mesas_status ON mesas(status);
CREATE INDEX IF NOT EXISTS idx_reservas_data ON reservas(data_reserva);

-- Trigger para atualizar updated_at automaticamente
CREATE TRIGGER IF NOT EXISTS update_comanda_itens_timestamp 
AFTER UPDATE ON comanda_itens
BEGIN
    UPDATE comanda_itens SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
END;

-- View para pedidos pendentes na cozinha/bar
CREATE VIEW IF NOT EXISTS vw_pedidos_preparo AS
SELECT 
    ci.id,
    ci.comanda_id,
    c.mesa_id,
    m.numero as mesa_numero,
    ci.produto_id,
    p.nome as produto_nome,
    p.local_preparo,
    ci.quantidade,
    ci.observacoes,
    ci.status,
    ci.created_at,
    TIMEDIFF(CURRENT_TIMESTAMP, ci.created_at) as tempo_espera,
    u.nome as garcom_nome
FROM comanda_itens ci
JOIN comandas c ON ci.comanda_id = c.id
JOIN mesas m ON c.mesa_id = m.id
JOIN produtos p ON ci.produto_id = p.id
JOIN usuarios u ON ci.garcom_id = u.id
WHERE ci.status IN ('pendente', 'preparando')
ORDER BY ci.created_at ASC;

-- View para relatório de vendas
CREATE VIEW IF NOT EXISTS vw_vendas_diarias AS
SELECT 
    DATE(c.data_fechamento) as data,
    COUNT(*) as total_comandas,
    SUM(c.total) as total_vendas,
    AVG(c.total) as ticket_medio,
    COUNT(ci.id) as total_itens_vendidos
FROM comandas c
LEFT JOIN comanda_itens ci ON c.id = ci.comanda_id
WHERE c.status = 'fechada'
GROUP BY DATE(c.data_fechamento);
```

### 2. Script para Popular Dados (02_insert_data.sql)

```sql
-- Script: Popular banco com dados iniciais
-- Inserir dados mínimos para sistema funcionar

-- Inserir Categorias
INSERT INTO categorias (nome, tipo, icone, ordem) VALUES
('Lanches', 'comida', '🍔', 1),
('Bebidas', 'bebida', '🥤', 2),
('Sobremesas', 'sobremesa', '🍰', 3),
('Acompanhamentos', 'comida', '🍟', 4),
('Pratos Executivos', 'comida', '🍛', 5),
('Bebidas Alcoólicas', 'bebida', '🍺', 6);

-- Inserir Produtos
INSERT INTO produtos (nome, categoria_id, preco, descricao, local_preparo, tempo_preparo, ingredientes) VALUES
-- Lanches
('Hambúrguer Artesanal', 1, 25.90, 'Pão brioche, carne 180g, queijo, alface, tomate', 'cozinha', 15, 'Carne, queijo, alface, tomate, pão brioche'),
('X-Bacon', 1, 29.90, 'Hambúrguer com bacon crocante', 'cozinha', 15, 'Carne, queijo, bacon, alface, tomate'),
('X-Salada', 1, 22.90, 'Hambúrguer com salada completa', 'cozinha', 12, 'Carne, queijo, alface, tomate, cebola'),

-- Bebidas
('Refrigerante Lata', 2, 8.00, 'Lata 350ml - Coca-Cola, Guaraná, Fanta', 'bar', 2, 'Refrigerante, gelo'),
('Suco Natural', 2, 10.00, 'Copo 500ml - Laranja, Limão, Abacaxi', 'bar', 5, 'Fruta, açúcar, água'),
('Água Mineral', 2, 5.00, 'Garrafa 500ml com ou sem gás', 'bar', 1, 'Água mineral'),

-- Sobremesas
('Brownie com Sorvete', 3, 18.90, 'Brownie quente com bola de sorvete', 'cozinha', 8, 'Chocolate, sorvete, farinha'),
('Mousse de Chocolate', 3, 12.90, 'Mousse cremoso de chocolate', 'cozinha', 2, 'Chocolate, creme de leite'),

-- Acompanhamentos
('Batata Frita', 4, 12.00, 'Porção de batata frita crocante', 'cozinha', 10, 'Batata, óleo, sal'),
('Onion Rings', 4, 14.90, 'Anéis de cebola empanados', 'cozinha', 12, 'Cebola, farinha de rosca, óleo'),

-- Bebidas Alcoólicas
('Cerveja Artesanal', 6, 18.90, 'Copo 500ml - IPA, Pilsen, Weiss', 'bar', 3, 'Cerveja, gelo'),
('Caipirinha', 6, 22.90, 'Tradicional com limão e cachaça', 'bar', 7, 'Limão, cachaça, açúcar, gelo');

-- Inserir Mesas
INSERT INTO mesas (numero, status, capacidade, localizacao) VALUES
(1, 'livre', 4, 'sala_principal'),
(2, 'ocupada', 6, 'sala_principal'),
(3, 'livre', 2, 'varanda'),
(4, 'reservada', 8, 'sala_vip'),
(5, 'livre', 4, 'terraco'),
(6, 'indisponivel', 4, 'sala_principal'),
(7, 'livre', 6, 'varanda'),
(8, 'livre', 4, 'terraco');

-- Inserir Usuários
INSERT INTO usuarios (nome, email, senha, tipo, pin, ativo) VALUES
('Administrador', 'admin@restaurante.com', 'admin123', 'admin', '9999', 1),
('Carlos Silva', 'carlos@restaurante.com', '123456', 'garcom', '1234', 1),
('Ana Souza', 'ana@restaurante.com', '123456', 'garcom', '5678', 1),
('João Cozinha', 'joao@restaurante.com', '123456', 'cozinha', '1111', 1),
('Maria Bar', 'maria@restaurante.com', '123456', 'cozinha', '2222', 1),
('Pedro Caixa', 'pedro@restaurante.com', '123456', 'caixa', '3333', 1);

-- Inserir Comanda de Exemplo
INSERT INTO comandas (mesa_id, garcom_id, cliente_nome, status, total, data_abertura) VALUES
(2, 2, 'João Silva', 'aberta', 67.80, datetime('now', '-1 hour'));

-- Inserir Itens da Comanda
INSERT INTO comanda_itens (comanda_id, produto_id, garcom_id, quantidade, preco_unitario, observacoes, status) VALUES
(1, 1, 2, 2, 25.90, 'Sem cebola', 'preparando'),
(1, 4, 2, 2, 8.00, 'Sem gelo', 'pendente');

-- Inserir Reserva de Exemplo
INSERT INTO reservas (mesa_id, cliente_nome, cliente_telefone, data_reserva, horario, quantidade_pessoas, observacoes) VALUES
(4, 'Maria Santos', '(11) 99999-9999', date('now', '+1 day'), '19:00', 6, 'Mesa perto da janela');

-- Atualizar mesa ocupada
UPDATE mesas SET comanda_aberta = 1, cliente_nome = 'João Silva' WHERE id = 2;

-- Atualizar mesa reservada
UPDATE mesas SET cliente_nome = 'Maria Santos' WHERE id = 4;
```

### 3. Script com Queries Úteis (03_queries_examples.sql)

```sql
-- Script: Queries úteis para o sistema

-- 1. Buscar mesas com status
SELECT 
    id,
    numero,
    status,
    capacidade,
    localizacao,
    CASE 
        WHEN status = 'livre' THEN '🟢 Livre'
        WHEN status = 'ocupada' THEN '🔴 Ocupada'
        WHEN status = 'reservada' THEN '🟡 Reservada'
        ELSE '⚫ Indisponível'
    END as status_display
FROM mesas 
ORDER BY numero;

-- 2. Buscar produtos por categoria
SELECT 
    p.id,
    p.nome,
    c.nome as categoria,
    p.preco,
    p.descricao,
    p.local_preparo,
    p.tempo_preparo
FROM produtos p
JOIN categorias c ON p.categoria_id = c.id
WHERE p.disponivel = 1
ORDER BY c.ordem, p.nome;

-- 3. Pedidos pendentes para cozinha
SELECT 
    ci.id as item_id,
    p.nome as produto,
    ci.quantidade,
    m.numero as mesa,
    ci.observacoes,
    ci.status,
    TIME(ci.created_at) as horario_pedido,
    u.nome as garcom
FROM comanda_itens ci
JOIN produtos p ON ci.produto_id = p.id
JOIN comandas co ON ci.comanda_id = co.id
JOIN mesas m ON co.mesa_id = m.id
JOIN usuarios u ON ci.garcom_id = u.id
WHERE p.local_preparo = 'cozinha' 
AND ci.status IN ('pendente', 'preparando')
ORDER BY ci.created_at;

-- 4. Pedidos pendentes para bar
SELECT 
    ci.id as item_id,
    p.nome as produto,
    ci.quantidade,
    m.numero as mesa,
    ci.observacoes,
    ci.status,
    TIME(ci.created_at) as horario_pedido,
    u.nome as garcom
FROM comanda_itens ci
JOIN produtos p ON ci.produto_id = p.id
JOIN comandas co ON ci.comanda_id = co.id
JOIN mesas m ON co.mesa_id = m.id
JOIN usuarios u ON ci.garcom_id = u.id
WHERE p.local_preparo = 'bar' 
AND ci.status IN ('pendente', 'preparando')
ORDER BY ci.created_at;

-- 5. Comanda ativa de uma mesa
SELECT 
    c.id as comanda_id,
    m.numero as mesa,
    c.cliente_nome,
    c.data_abertura,
    SUM(ci.quantidade * ci.preco_unitario) as total_atual
FROM comandas c
JOIN mesas m ON c.mesa_id = m.id
LEFT JOIN comanda_itens ci ON c.id = ci.comanda_id
WHERE c.mesa_id = 2 AND c.status = 'aberta'
GROUP BY c.id;

-- 6. Itens de uma comanda específica
SELECT 
    ci.id,
    p.nome as produto,
    ci.quantidade,
    ci.preco_unitario,
    (ci.quantidade * ci.preco_unitario) as subtotal,
    ci.observacoes,
    ci.status
FROM comanda_itens ci
JOIN produtos p ON ci.produto_id = p.id
WHERE ci.comanda_id = 1;

-- 7. Fechar comanda (exemplo de transação)
BEGIN TRANSACTION;

-- Atualizar status dos itens para entregue
UPDATE comanda_itens 
SET status = 'entregue' 
WHERE comanda_id = 1 AND status != 'cancelado';

-- Calcular total
UPDATE comandas 
SET total = (
    SELECT SUM(quantidade * preco_unitario) 
    FROM comanda_itens 
    WHERE comanda_id = 1 AND status != 'cancelado'
),
status = 'fechada',
data_fechamento = CURRENT_TIMESTAMP
WHERE id = 1;

-- Liberar mesa
UPDATE mesas 
SET status = 'livre', 
    comanda_aberta = 0, 
    cliente_nome = NULL 
WHERE id = (SELECT mesa_id FROM comandas WHERE id = 1);

COMMIT;

-- 8. Relatório de vendas do dia
SELECT 
    DATE(data_fechamento) as data,
    COUNT(*) as total_vendas,
    SUM(total) as receita_total,
    AVG(total) as ticket_medio,
    MIN(total) as menor_venda,
    MAX(total) as maior_venda
FROM comandas 
WHERE status = 'fechada' 
AND DATE(data_fechamento) = DATE('now')
GROUP BY DATE(data_fechamento);

-- 9. Produtos mais vendidos
SELECT 
    p.nome as produto,
    c.nome as categoria,
    SUM(ci.quantidade) as total_vendido,
    SUM(ci.quantidade * ci.preco_unitario) as receita_total
FROM comanda_itens ci
JOIN produtos p ON ci.produto_id = p.id
JOIN categorias c ON p.categoria_id = c.id
JOIN comandas co ON ci.comanda_id = co.id
WHERE co.status = 'fechada'
AND DATE(co.data_fechamento) = DATE('now')
GROUP BY p.id
ORDER BY total_vendido DESC
LIMIT 10;
```

## 🔧 Configuração do Banco com Node.js

### database/config/database.js

```javascript
const sqlite3 = require('sqlite3').verbose();
const path = require('path');

class Database {
    constructor() {
        this.dbPath = path.join(__dirname, '..', 'comanda.db');
        this.db = null;
    }

    connect() {
        return new Promise((resolve, reject) => {
            this.db = new sqlite3.Database(this.dbPath, (err) => {
                if (err) {
                    console.error('Erro ao conectar com banco:', err.message);
                    reject(err);
                } else {
                    console.log('✅ Conectado ao SQLite Database');
                    resolve(this.db);
                }
            });
        });
    }

    disconnect() {
        if (this.db) {
            this.db.close((err) => {
                if (err) {
                    console.error('Erro ao fechar banco:', err.message);
                } else {
                    console.log('✅ Conexão com banco fechada');
                }
            });
        }
    }

    // Executar script SQL
    exec(sql) {
        return new Promise((resolve, reject) => {
            this.db.exec(sql, (err) => {
                if (err) {
                    reject(err);
                } else {
                    resolve();
                }
            });
        });
    }

    // Executar query
    run(sql, params = []) {
        return new Promise((resolve, reject) => {
            this.db.run(sql, params, function(err) {
                if (err) {
                    reject(err);
                } else {
                    resolve({ id: this.lastID, changes: this.changes });
                }
            });
        });
    }

    // Buscar um registro
    get(sql, params = []) {
        return new Promise((resolve, reject) => {
            this.db.get(sql, params, (err, row) => {
                if (err) {
                    reject(err);
                } else {
                    resolve(row);
                }
            });
        });
    }

    // Buscar todos os registros
    all(sql, params = []) {
        return new Promise((resolve, reject) => {
            this.db.all(sql, params, (err, rows) => {
                if (err) {
                    reject(err);
                } else {
                    resolve(rows);
                }
            });
        });
    }
}

// Singleton para usar a mesma instância em toda aplicação
let databaseInstance = null;

function getDatabase() {
    if (!databaseInstance) {
        databaseInstance = new Database();
    }
    return databaseInstance;
}

module.exports = { Database, getDatabase };
```

### Exemplo de Uso no Backend

```javascript
const { getDatabase } = require('./config/database');

// Inicializar banco de dados
async function initializeDatabase() {
    const db = getDatabase();
    
    try {
        await db.connect();
        
        // Executar scripts de criação
        const fs = require('fs');
        const path = require('path');
        
        const createScript = fs.readFileSync(
            path.join(__dirname, '../scripts/01_create_database.sql'), 
            'utf8'
        );
        
        const insertScript = fs.readFileSync(
            path.join(__dirname, '../scripts/02_insert_data.sql'), 
            'utf8'
        );
        
        await db.exec(createScript);
        console.log('✅ Estrutura do banco criada');
        
        await db.exec(insertScript);
        console.log('✅ Dados iniciais inseridos');
        
    } catch (error) {
        console.error('❌ Erro ao inicializar banco:', error);
    }
}

// Exemplo de uso nas rotas
async function getMesas() {
    const db = getDatabase();
    try {
        const mesas = await db.all(`
            SELECT m.*, 
                   COUNT(c.id) as comandas_ativas
            FROM mesas m
            LEFT JOIN comandas c ON m.id = c.mesa_id AND c.status = 'aberta'
            GROUP BY m.id
            ORDER BY m.numero
        `);
        return mesas;
    } catch (error) {
        throw error;
    }
}

// Exportar funções
module.exports = {
    initializeDatabase,
    getMesas
};
```

## 🚀 Como Executar

### 1. Instalar SQLite3
```bash
# Para Node.js
npm install sqlite3

# Para linha de comando (opcional)
sudo apt install sqlite3  # Linux
brew install sqlite3      # macOS
```

### 2. Executar Scripts
```bash
# Via Node.js
node -e "
const { initializeDatabase } = require('./database/config/database');
initializeDatabase();
"

# Via linha de comando SQLite
sqlite3 comanda.db < scripts/01_create_database.sql
sqlite3 comanda.db < scripts/02_insert_data.sql
```

### 3. Verificar Banco
```bash
sqlite3 comanda.db
.tables
.schema
SELECT * FROM mesas;
```

## 📊 Estrutura Final do Banco

Após executar os scripts, você terá:

- **8 tabelas** principais
- **2 views** para relatórios
- **1 trigger** para timestamps
- Dados de exemplo para testar
- Índices para performance

Este banco SQLite fornece uma base sólida e leve para o sistema de comanda eletrônica, perfeita para pequenos e médios estabelecimentos!