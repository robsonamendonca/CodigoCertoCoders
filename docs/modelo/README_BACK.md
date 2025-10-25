# Tutorial: Backend para Sistema de Comanda Eletrônica com Node.js

## 📋 Introdução

Este tutorial cria uma API Fake com Node.js para integrar com o frontend do sistema de comanda eletrônica, usando dados mocados em JSON.

## 🏗️ Estrutura do Projeto Backend

```
backend-comanda/
├── package.json
├── server.js
├── data/
│   ├── mesas.json
│   ├── produtos.json
│   ├── pedidos.json
│   ├── usuarios.json
│   └── categorias.json
├── routes/
│   ├── mesas.js
│   ├── produtos.js
│   ├── pedidos.js
│   └── auth.js
└── middleware/
    └── cors.js
```

## 📝 Código do Backend

### 1. package.json

```json
{
  "name": "comanda-backend",
  "version": "1.0.0",
  "description": "API Fake para sistema de comanda eletrônica",
  "main": "server.js",
  "scripts": {
    "start": "node server.js",
    "dev": "nodemon server.js"
  },
  "dependencies": {
    "express": "^4.18.2",
    "cors": "^2.8.5",
    "body-parser": "^1.20.2",
    "uuid": "^9.0.0"
  },
  "devDependencies": {
    "nodemon": "^3.0.1"
  }
}
```

### 2. server.js (Servidor Principal)

```javascript
const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');
const path = require('path');

// Importar rotas
const mesasRoutes = require('./routes/mesas');
const produtosRoutes = require('./routes/produtos');
const pedidosRoutes = require('./routes/pedidos');
const authRoutes = require('./routes/auth');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());
app.use(express.static('public'));

// Rotas
app.use('/api/mesas', mesasRoutes);
app.use('/api/produtos', produtosRoutes);
app.use('/api/pedidos', pedidosRoutes);
app.use('/api/auth', authRoutes);

// Rota de health check
app.get('/api/health', (req, res) => {
    res.json({ 
        status: 'OK', 
        message: 'API Comanda Eletrônica funcionando!',
        timestamp: new Date().toISOString()
    });
});

// Inicializar servidor
app.listen(PORT, () => {
    console.log(`🚀 Servidor rodando na porta ${PORT}`);
    console.log(`📊 API disponível em: http://localhost:${PORT}/api`);
    console.log(`🔗 Health check: http://localhost:${PORT}/api/health`);
});

module.exports = app;
```

### 3. Dados Mockados

#### data/mesas.json
```json
[
  {
    "id": 1,
    "numero": 1,
    "status": "livre",
    "capacidade": 4,
    "localizacao": "varanda",
    "comanda_aberta": false,
    "cliente_nome": null
  },
  {
    "id": 2,
    "numero": 2,
    "status": "ocupada",
    "capacidade": 6,
    "localizacao": "sala_principal",
    "comanda_aberta": true,
    "cliente_nome": "João Silva"
  },
  {
    "id": 3,
    "numero": 3,
    "status": "livre",
    "capacidade": 2,
    "localizacao": "varanda",
    "comanda_aberta": false,
    "cliente_nome": null
  },
  {
    "id": 4,
    "numero": 4,
    "status": "reservada",
    "capacidade": 8,
    "localizacao": "sala_vip",
    "comanda_aberta": false,
    "cliente_nome": "Maria Santos",
    "reserva_horario": "19:00"
  }
]
```

#### data/produtos.json
```json
[
  {
    "id": 1,
    "nome": "Hambúrguer Artesanal",
    "categoria": "comida",
    "subcategoria": "lanches",
    "preco": 25.90,
    "descricao": "Pão brioche, carne 180g, queijo, alface, tomate",
    "disponivel": true,
    "local_preparo": "cozinha",
    "tempo_preparo": 15,
    "ingredientes": ["carne", "queijo", "alface", "tomate", "pão brioche"]
  },
  {
    "id": 2,
    "nome": "Batata Frita",
    "categoria": "comida",
    "subcategoria": "acompanhamentos",
    "preco": 12.00,
    "descricao": "Porção de batata frita crocante",
    "disponivel": true,
    "local_preparo": "cozinha",
    "tempo_preparo": 10,
    "ingredientes": ["batata", "óleo", "sal"]
  },
  {
    "id": 3,
    "nome": "Refrigerante",
    "categoria": "bebida",
    "subcategoria": "refrigerantes",
    "preco": 8.00,
    "descricao": "Lata 350ml",
    "disponivel": true,
    "local_preparo": "bar",
    "tempo_preparo": 2,
    "ingredientes": ["refrigerante", "gelo"]
  },
  {
    "id": 4,
    "nome": "Suco Natural",
    "categoria": "bebida",
    "subcategoria": "sucos",
    "preco": 10.00,
    "descricao": "Copo 500ml",
    "disponivel": true,
    "local_preparo": "bar",
    "tempo_preparo": 5,
    "ingredientes": ["fruta", "açúcar", "água"]
  }
]
```

#### data/categorias.json
```json
[
  {
    "id": 1,
    "nome": "Lanches",
    "tipo": "comida",
    "icone": "🍔",
    "ordem": 1
  },
  {
    "id": 2,
    "nome": "Bebidas",
    "tipo": "bebida",
    "icone": "🥤",
    "ordem": 2
  },
  {
    "id": 3,
    "nome": "Sobremesas",
    "tipo": "comida",
    "icone": "🍰",
    "ordem": 3
  },
  {
    "id": 4,
    "nome": "Acompanhamentos",
    "tipo": "comida",
    "icone": "🍟",
    "ordem": 4
  }
]
```

#### data/usuarios.json
```json
[
  {
    "id": 1,
    "nome": "Carlos Silva",
    "email": "carlos@restaurante.com",
    "senha": "123456",
    "tipo": "garcom",
    "ativo": true,
    "pin": "1234"
  },
  {
    "id": 2,
    "nome": "Ana Souza",
    "email": "ana@restaurante.com",
    "senha": "123456",
    "tipo": "cozinha",
    "ativo": true,
    "pin": "5678"
  },
  {
    "id": 3,
    "nome": "Admin",
    "email": "admin@restaurante.com",
    "senha": "admin123",
    "tipo": "admin",
    "ativo": true,
    "pin": "9999"
  }
]
```

#### data/pedidos.json
```json
[
  {
    "id": 1,
    "mesa_id": 2,
    "garcom_id": 1,
    "cliente_nome": "João Silva",
    "itens": [
      {
        "id": 1,
        "produto_id": 1,
        "quantidade": 2,
        "preco_unitario": 25.90,
        "observacoes": "Sem cebola",
        "status": "preparando",
        "local_preparo": "cozinha"
      },
      {
        "id": 2,
        "produto_id": 3,
        "quantidade": 2,
        "preco_unitario": 8.00,
        "observacoes": "Sem gelo",
        "status": "pendente",
        "local_preparo": "bar"
      }
    ],
    "status": "aberto",
    "total": 67.80,
    "data_abertura": "2024-01-15T18:30:00Z",
    "data_fechamento": null
  }
]
```

### 4. Rotas da API

#### routes/mesas.js
```javascript
const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');

const mesasPath = path.join(__dirname, '../data/mesas.json');

// Helper para ler dados
const readMesas = () => {
    return JSON.parse(fs.readFileSync(mesasPath, 'utf8'));
};

// Helper para escrever dados
const writeMesas = (data) => {
    fs.writeFileSync(mesasPath, JSON.stringify(data, null, 2));
};

// GET /api/mesas - Listar todas as mesas
router.get('/', (req, res) => {
    try {
        const mesas = readMesas();
        res.json(mesas);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao carregar mesas' });
    }
});

// GET /api/mesas/:id - Buscar mesa por ID
router.get('/:id', (req, res) => {
    try {
        const mesas = readMesas();
        const mesa = mesas.find(m => m.id === parseInt(req.params.id));
        
        if (!mesa) {
            return res.status(404).json({ error: 'Mesa não encontrada' });
        }
        
        res.json(mesa);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao buscar mesa' });
    }
});

// PUT /api/mesas/:id - Atualizar mesa
router.put('/:id', (req, res) => {
    try {
        const mesas = readMesas();
        const mesaIndex = mesas.findIndex(m => m.id === parseInt(req.params.id));
        
        if (mesaIndex === -1) {
            return res.status(404).json({ error: 'Mesa não encontrada' });
        }
        
        // Atualizar mesa
        mesas[mesaIndex] = { ...mesas[mesaIndex], ...req.body };
        writeMesas(mesas);
        
        res.json(mesas[mesaIndex]);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao atualizar mesa' });
    }
});

// POST /api/mesas/:id/abrir-comanda - Abrir comanda
router.post('/:id/abrir-comanda', (req, res) => {
    try {
        const { cliente_nome } = req.body;
        const mesas = readMesas();
        const mesaIndex = mesas.findIndex(m => m.id === parseInt(req.params.id));
        
        if (mesaIndex === -1) {
            return res.status(404).json({ error: 'Mesa não encontrada' });
        }
        
        // Atualizar status da mesa
        mesas[mesaIndex].status = 'ocupada';
        mesas[mesaIndex].comanda_aberta = true;
        mesas[mesaIndex].cliente_nome = cliente_nome || 'Cliente';
        
        writeMesas(mesas);
        
        res.json({
            success: true,
            message: 'Comanda aberta com sucesso',
            mesa: mesas[mesaIndex]
        });
    } catch (error) {
        res.status(500).json({ error: 'Erro ao abrir comanda' });
    }
});

// POST /api/mesas/:id/fechar-comanda - Fechar comanda
router.post('/:id/fechar-comanda', (req, res) => {
    try {
        const mesas = readMesas();
        const mesaIndex = mesas.findIndex(m => m.id === parseInt(req.params.id));
        
        if (mesaIndex === -1) {
            return res.status(404).json({ error: 'Mesa não encontrada' });
        }
        
        // Atualizar status da mesa
        mesas[mesaIndex].status = 'livre';
        mesas[mesaIndex].comanda_aberta = false;
        mesas[mesaIndex].cliente_nome = null;
        
        writeMesas(mesas);
        
        res.json({
            success: true,
            message: 'Comanda fechada com sucesso',
            mesa: mesas[mesaIndex]
        });
    } catch (error) {
        res.status(500).json({ error: 'Erro ao fechar comanda' });
    }
});

module.exports = router;
```

#### routes/produtos.js
```javascript
const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');

const produtosPath = path.join(__dirname, '../data/produtos.json');
const categoriasPath = path.join(__dirname, '../data/categorias.json');

// Helper para ler dados
const readProdutos = () => {
    return JSON.parse(fs.readFileSync(produtosPath, 'utf8'));
};

const readCategorias = () => {
    return JSON.parse(fs.readFileSync(categoriasPath, 'utf8'));
};

// GET /api/produtos - Listar todos os produtos
router.get('/', (req, res) => {
    try {
        const { categoria, disponivel } = req.query;
        let produtos = readProdutos();
        
        // Filtros
        if (categoria) {
            produtos = produtos.filter(p => p.categoria === categoria);
        }
        
        if (disponivel !== undefined) {
            produtos = produtos.filter(p => p.disponivel === (disponivel === 'true'));
        }
        
        res.json(produtos);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao carregar produtos' });
    }
});

// GET /api/produtos/categorias - Listar categorias
router.get('/categorias', (req, res) => {
    try {
        const categorias = readCategorias();
        res.json(categorias);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao carregar categorias' });
    }
});

// GET /api/produtos/:id - Buscar produto por ID
router.get('/:id', (req, res) => {
    try {
        const produtos = readProdutos();
        const produto = produtos.find(p => p.id === parseInt(req.params.id));
        
        if (!produto) {
            return res.status(404).json({ error: 'Produto não encontrado' });
        }
        
        res.json(produto);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao buscar produto' });
    }
});

module.exports = router;
```

#### routes/pedidos.js
```javascript
const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');
const { v4: uuidv4 } = require('uuid');

const pedidosPath = path.join(__dirname, '../data/pedidos.json');
const produtosPath = path.join(__dirname, '../data/produtos.json');
const mesasPath = path.join(__dirname, '../data/mesas.json');

// Helper para ler/escrever dados
const readPedidos = () => {
    return JSON.parse(fs.readFileSync(pedidosPath, 'utf8'));
};

const writePedidos = (data) => {
    fs.writeFileSync(pedidosPath, JSON.stringify(data, null, 2));
};

const readProdutos = () => {
    return JSON.parse(fs.readFileSync(produtosPath, 'utf8'));
};

const readMesas = () => {
    return JSON.parse(fs.readFileSync(mesasPath, 'utf8'));
};

// GET /api/pedidos - Listar pedidos
router.get('/', (req, res) => {
    try {
        const { status, local_preparo } = req.query;
        let pedidos = readPedidos();
        
        // Filtros
        if (status) {
            pedidos = pedidos.filter(p => p.status === status);
        }
        
        if (local_preparo) {
            pedidos = pedidos.filter(p => 
                p.itens.some(item => item.local_preparo === local_preparo && item.status !== 'entregue')
            );
        }
        
        res.json(pedidos);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao carregar pedidos' });
    }
});

// POST /api/pedidos - Criar novo pedido
router.post('/', (req, res) => {
    try {
        const { mesa_id, garcom_id, cliente_nome, itens } = req.body;
        
        // Validar mesa
        const mesas = readMesas();
        const mesa = mesas.find(m => m.id === mesa_id);
        if (!mesa) {
            return res.status(404).json({ error: 'Mesa não encontrada' });
        }
        
        // Validar produtos
        const produtos = readProdutos();
        const itensValidados = itens.map(item => {
            const produto = produtos.find(p => p.id === item.produto_id);
            if (!produto) {
                throw new Error(`Produto ${item.produto_id} não encontrado`);
            }
            
            return {
                id: uuidv4(),
                produto_id: item.produto_id,
                quantidade: item.quantidade,
                preco_unitario: produto.preco,
                observacoes: item.observacoes || '',
                status: 'pendente',
                local_preparo: produto.local_preparo,
                nome_produto: produto.nome
            };
        });
        
        // Calcular total
        const total = itensValidados.reduce((sum, item) => 
            sum + (item.preco_unitario * item.quantidade), 0
        );
        
        // Criar pedido
        const novoPedido = {
            id: Date.now(),
            mesa_id,
            garcom_id,
            cliente_nome: cliente_nome || mesa.cliente_nome || 'Cliente',
            itens: itensValidados,
            status: 'aberto',
            total,
            data_abertura: new Date().toISOString(),
            data_fechamento: null
        };
        
        // Salvar pedido
        const pedidos = readPedidos();
        pedidos.push(novoPedido);
        writePedidos(pedidos);
        
        res.status(201).json({
            success: true,
            message: 'Pedido criado com sucesso',
            pedido: novoPedido
        });
        
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// PUT /api/pedidos/:id/item/:itemId/status - Atualizar status do item
router.put('/:id/item/:itemId/status', (req, res) => {
    try {
        const { status } = req.body;
        const pedidos = readPedidos();
        const pedidoIndex = pedidos.findIndex(p => p.id === parseInt(req.params.id));
        
        if (pedidoIndex === -1) {
            return res.status(404).json({ error: 'Pedido não encontrado' });
        }
        
        const itemIndex = pedidos[pedidoIndex].itens.findIndex(
            item => item.id === req.params.itemId
        );
        
        if (itemIndex === -1) {
            return res.status(404).json({ error: 'Item não encontrado' });
        }
        
        // Atualizar status do item
        pedidos[pedidoIndex].itens[itemIndex].status = status;
        writePedidos(pedidos);
        
        res.json({
            success: true,
            message: 'Status atualizado com sucesso',
            item: pedidos[pedidoIndex].itens[itemIndex]
        });
        
    } catch (error) {
        res.status(500).json({ error: 'Erro ao atualizar status' });
    }
});

// GET /api/pedidos/mesa/:mesa_id - Buscar pedidos da mesa
router.get('/mesa/:mesa_id', (req, res) => {
    try {
        const pedidos = readPedidos();
        const pedidosMesa = pedidos.filter(p => 
            p.mesa_id === parseInt(req.params.mesa_id) && p.status === 'aberto'
        );
        
        res.json(pedidosMesa);
    } catch (error) {
        res.status(500).json({ error: 'Erro ao buscar pedidos da mesa' });
    }
});

module.exports = router;
```

#### routes/auth.js
```javascript
const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');

const usuariosPath = path.join(__dirname, '../data/usuarios.json');

// Helper para ler dados
const readUsuarios = () => {
    return JSON.parse(fs.readFileSync(usuariosPath, 'utf8'));
};

// POST /api/auth/login - Login do usuário
router.post('/login', (req, res) => {
    try {
        const { email, senha, pin } = req.body;
        const usuarios = readUsuarios();
        
        // Buscar usuário por email ou PIN
        const usuario = usuarios.find(u => 
            (email && u.email === email && u.senha === senha) ||
            (pin && u.pin === pin)
        );
        
        if (!usuario || !usuario.ativo) {
            return res.status(401).json({ 
                error: 'Credenciais inválidas ou usuário inativo' 
            });
        }
        
        // Remover senha do response
        const { senha: _, ...usuarioSemSenha } = usuario;
        
        res.json({
            success: true,
            message: 'Login realizado com sucesso',
            usuario: usuarioSemSenha,
            token: 'fake-jwt-token-' + usuario.id
        });
        
    } catch (error) {
        res.status(500).json({ error: 'Erro ao fazer login' });
    }
});

// GET /api/auth/me - Buscar usuário atual (simulado)
router.get('/me', (req, res) => {
    try {
        const token = req.headers.authorization;
        
        if (!token) {
            return res.status(401).json({ error: 'Token não fornecido' });
        }
        
        // Simular busca do usuário pelo token
        const usuarios = readUsuarios();
        const userId = token.replace('fake-jwt-token-', '');
        const usuario = usuarios.find(u => u.id === parseInt(userId));
        
        if (!usuario) {
            return res.status(401).json({ error: 'Token inválido' });
        }
        
        const { senha: _, ...usuarioSemSenha } = usuario;
        res.json(usuarioSemSenha);
        
    } catch (error) {
        res.status(500).json({ error: 'Erro ao buscar usuário' });
    }
});

module.exports = router;
```

## 🔧 Configuração e Instalação

### 1. Instalar dependências:
```bash
cd backend-comanda
npm install
```

### 2. Executar o servidor:
```bash
# Desenvolvimento
npm run dev

# Produção
npm start
```

## 🌐 Endpoints da API

### Autenticação
- `POST /api/auth/login` - Login de usuário
- `GET /api/auth/me` - Buscar usuário atual

### Mesas
- `GET /api/mesas` - Listar todas as mesas
- `GET /api/mesas/:id` - Buscar mesa por ID
- `PUT /api/mesas/:id` - Atualizar mesa
- `POST /api/mesas/:id/abrir-comanda` - Abrir comanda
- `POST /api/mesas/:id/fechar-comanda` - Fechar comanda

### Produtos
- `GET /api/produtos` - Listar produtos
- `GET /api/produtos/categorias` - Listar categorias
- `GET /api/produtos/:id` - Buscar produto

### Pedidos
- `GET /api/pedidos` - Listar pedidos
- `POST /api/pedidos` - Criar pedido
- `PUT /api/pedidos/:id/item/:itemId/status` - Atualizar status do item
- `GET /api/pedidos/mesa/:mesa_id` - Pedidos da mesa

## 🔄 Integração com Frontend

### Exemplo de uso no JavaScript do frontend:

```javascript
class ComandaAPI {
    constructor() {
        this.baseURL = 'http://localhost:3000/api';
        this.token = localStorage.getItem('authToken');
    }

    async request(endpoint, options = {}) {
        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...(this.token && { 'Authorization': this.token }),
                ...options.headers
            },
            ...options
        };

        const response = await fetch(`${this.baseURL}${endpoint}`, config);
        return await response.json();
    }

    // Autenticação
    async login(credentials) {
        return this.request('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials)
        });
    }

    // Mesas
    async getMesas() {
        return this.request('/mesas');
    }

    async abrirComanda(mesaId, clienteNome) {
        return this.request(`/mesas/${mesaId}/abrir-comanda`, {
            method: 'POST',
            body: JSON.stringify({ cliente_nome: clienteNome })
        });
    }

    // Produtos
    async getProdutos() {
        return this.request('/produtos');
    }

    async getCategorias() {
        return this.request('/produtos/categorias');
    }

    // Pedidos
    async criarPedido(pedidoData) {
        return this.request('/pedidos', {
            method: 'POST',
            body: JSON.stringify(pedidoData)
        });
    }

    async getPedidosAtivos() {
        return this.request('/pedidos?status=aberto');
    }

    async atualizarStatusItem(pedidoId, itemId, status) {
        return this.request(`/pedidos/${pedidoId}/item/${itemId}/status`, {
            method: 'PUT',
            body: JSON.stringify({ status })
        });
    }
}

// Uso no sistema
const api = new ComandaAPI();

// Exemplo de login
api.login({ email: 'carlos@restaurante.com', senha: '123456' })
   .then(response => {
       if (response.success) {
           localStorage.setItem('authToken', response.token);
       }
   });

// Exemplo de carregar mesas
api.getMesas().then(mesas => {
    console.log('Mesas:', mesas);
});
```

## 🚀 Funcionalidades do Backend

### ✅ Implementadas:
- ✅ API REST completa
- ✅ Autenticação simulada
- ✅ CRUD de mesas, produtos e pedidos
- ✅ Filtros e buscas
- ✅ Atualização de status em tempo real
- ✅ Cálculo automático de totais

### 📊 Dados Persistidos:
- Mesas com status e comandas
- Produtos com categorias e preços
- Pedidos com itens e status
- Usuários com diferentes perfis

Este backend fornece uma base sólida para integrar com o frontend e criar um sistema completo de comanda eletrônica!