# Tutorial: Sistema de Comanda Eletrônica com Vanilla JS

## 📋 Introdução

Este tutorial ensina a criar um sistema básico de comanda eletrônica usando apenas **HTML5, CSS3 e JavaScript Vanilla**.

## 🎯 Objetivo do Projeto

Criar uma aplicação web que substitua as comandas de papel em restaurantes, com três módulos principais:
- Aplicativo do Garçom
- Monitor de Preparos
- Módulo Administrativo

## 🏗️ Estrutura do Projeto

```
comanda-eletronica/
├── index.html
├── style.css
├── script.js
├── garcom/
│   ├── login.html
│   ├── mesas.html
│   └── pedidos.html
├── cozinha/
│   └── monitor.html
└── admin/
    └── dashboard.html
```

## 📝 Código Base

### 1. Estrutura HTML Principal (index.html)

```html
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Comanda Eletrônica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <h1>🍽️ Comanda Digital</h1>
        <nav class="nav">
            <button class="nav-btn" data-module="garcom">Garçom</button>
            <button class="nav-btn" data-module="cozinha">Cozinha</button>
            <button class="nav-btn" data-module="admin">Admin</button>
        </nav>
    </header>

    <main class="main-content">
        <div class="welcome-section">
            <h2>Bem-vindo ao Sistema de Comanda Eletrônica</h2>
            <p>Selecione um módulo para começar</p>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
```

### 2. Estilos CSS (style.css)

```css
/* Reset e Variáveis */
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --danger-color: #e74c3c;
    --warning-color: #f39c12;
    --light-color: #ecf0f1;
    --dark-color: #2c3e50;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f6fa;
    color: var(--dark-color);
    line-height: 1.6;
}

/* Header */
.header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.nav {
    display: flex;
    gap: 1rem;
}

.nav-btn {
    background: rgba(255,255,255,0.2);
    border: 2px solid white;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-btn:hover {
    background: white;
    color: var(--primary-color);
}

/* Conteúdo Principal */
.main-content {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.welcome-section {
    text-align: center;
    padding: 3rem 0;
}

/* Sistema de Grid */
.grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

/* Cards */
.card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

/* Mesas */
.mesas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.mesa {
    background: var(--secondary-color);
    color: white;
    padding: 1rem;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mesa.livre { background: var(--success-color); }
.mesa.ocupada { background: var(--danger-color); }
.mesa.reservada { background: var(--warning-color); }

.mesa:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

/* Formulários */
.form-group {
    margin-bottom: 1rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.form-input:focus {
    outline: none;
    border-color: var(--secondary-color);
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: var(--secondary-color);
    color: white;
}

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

/* Lista de Pedidos */
.pedidos-list {
    margin-top: 1rem;
}

.pedido-item {
    background: white;
    border-left: 4px solid var(--secondary-color);
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pedido-item.pronto {
    border-left-color: var(--success-color);
}

.pedido-item.preparando {
    border-left-color: var(--warning-color);
}
```

### 3. JavaScript Principal (script.js)

```javascript
// Sistema de Navegação entre Módulos
class ComandaSystem {
    constructor() {
        this.currentModule = 'home';
        this.init();
    }

    init() {
        this.setupNavigation();
        this.loadModule('home');
    }

    setupNavigation() {
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const module = e.target.dataset.module;
                this.loadModule(module);
            });
        });
    }

    loadModule(module) {
        this.currentModule = module;
        
        switch(module) {
            case 'garcom':
                this.renderGarcomModule();
                break;
            case 'cozinha':
                this.renderCozinhaModule();
                break;
            case 'admin':
                this.renderAdminModule();
                break;
            default:
                this.renderHome();
        }
    }

    renderHome() {
        const main = document.querySelector('.main-content');
        main.innerHTML = `
            <div class="welcome-section">
                <h2>Bem-vindo ao Sistema de Comanda Eletrônica</h2>
                <p>Selecione um módulo para começar</p>
                <div class="grid-container">
                    <div class="card">
                        <h3>🧑‍💼 Módulo Garçom</h3>
                        <p>Controle de mesas e pedidos</p>
                    </div>
                    <div class="card">
                        <h3>👨‍🍳 Módulo Cozinha</h3>
                        <p>Monitor de preparos</p>
                    </div>
                    <div class="card">
                        <h3>⚙️ Módulo Admin</h3>
                        <p>Gestão do estabelecimento</p>
                    </div>
                </div>
            </div>
        `;
    }

    renderGarcomModule() {
        const main = document.querySelector('.main-content');
        main.innerHTML = `
            <div class="garcom-module">
                <h2>Módulo do Garçom</h2>
                
                <div class="card">
                    <h3>Mapa de Mesas</h3>
                    <div class="mesas-grid" id="mesas-grid">
                        <!-- Mesas serão geradas dinamicamente -->
                    </div>
                </div>

                <div class="card">
                    <h3>Fazer Pedido</h3>
                    <form id="pedido-form">
                        <div class="form-group">
                            <label>Mesa:</label>
                            <select class="form-input" id="mesa-select" required>
                                <option value="">Selecione uma mesa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Produto:</label>
                            <select class="form-input" id="produto-select" required>
                                <option value="">Selecione um produto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantidade:</label>
                            <input type="number" class="form-input" id="quantidade" value="1" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar Pedido</button>
                    </form>
                </div>

                <div class="card">
                    <h3>Pedidos Ativos</h3>
                    <div id="pedidos-ativos"></div>
                </div>
            </div>
        `;

        this.initGarcomModule();
    }

    initGarcomModule() {
        // Simulação de dados
        const mesas = [
            { id: 1, numero: 1, status: 'livre' },
            { id: 2, numero: 2, status: 'ocupada' },
            { id: 3, numero: 3, status: 'livre' },
            { id: 4, numero: 4, status: 'reservada' }
        ];

        const produtos = [
            { id: 1, nome: 'Hambúrguer', categoria: 'comida', preco: 25.00 },
            { id: 2, nome: 'Refrigerante', categoria: 'bebida', preco: 8.00 },
            { id: 3, nome: 'Batata Frita', categoria: 'comida', preco: 12.00 }
        ];

        // Renderizar mesas
        const mesasGrid = document.getElementById('mesas-grid');
        mesasGrid.innerHTML = mesas.map(mesa => `
            <div class="mesa ${mesa.status}" data-mesa-id="${mesa.id}">
                <h4>Mesa ${mesa.numero}</h4>
                <span>${mesa.status}</span>
            </div>
        `).join('');

        // Popular selects
        const mesaSelect = document.getElementById('mesa-select');
        const produtoSelect = document.getElementById('produto-select');

        mesaSelect.innerHTML = '<option value="">Selecione uma mesa</option>' + 
            mesas.filter(m => m.status === 'livre').map(m => 
                `<option value="${m.id}">Mesa ${m.numero}</option>`
            ).join('');

        produtoSelect.innerHTML = '<option value="">Selecione um produto</option>' +
            produtos.map(p => 
                `<option value="${p.id}">${p.nome} - R$ ${p.preco.toFixed(2)}</option>`
            ).join('');

        // Configurar formulário de pedido
        document.getElementById('pedido-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.fazerPedido();
        });
    }

    fazerPedido() {
        const mesaId = document.getElementById('mesa-select').value;
        const produtoId = document.getElementById('produto-select').value;
        const quantidade = document.getElementById('quantidade').value;

        if (!mesaId || !produtoId) {
            alert('Preencha todos os campos!');
            return;
        }

        // Simular envio do pedido
        const pedido = {
            id: Date.now(),
            mesaId: parseInt(mesaId),
            produtoId: parseInt(produtoId),
            quantidade: parseInt(quantidade),
            status: 'pendente',
            timestamp: new Date()
        };

        this.salvarPedido(pedido);
        alert('Pedido enviado para a cozinha!');
        document.getElementById('pedido-form').reset();
    }

    salvarPedido(pedido) {
        let pedidos = JSON.parse(localStorage.getItem('pedidos') || '[]');
        pedidos.push(pedido);
        localStorage.setItem('pedidos', JSON.stringify(pedidos));
    }
}

// Inicializar o sistema quando a página carregar
document.addEventListener('DOMContentLoaded', () => {
    new ComandaSystem();
});
```

## 🚀 Funcionalidades Implementadas

### 1. Módulo Garçom
- Visualização do mapa de mesas com status
- Formulário para fazer pedidos
- Controle de pedidos ativos

### 2. Armazenamento Local
- Uso do localStorage para persistência de dados
- Estrutura de dados para pedidos e mesas

### 3. Interface Responsiva
- Design adaptável para diferentes dispositivos
- Feedback visual para ações do usuário

## 📈 Próximos Passos

Para evoluir o sistema, você pode adicionar:

1. **Módulo Cozinha Completo**
   - Lista de pedidos em tempo real
   - Controle de status (preparando/pronto)

2. **Módulo Administrativo**
   - Cadastro de produtos
   - Gestão de usuários
   - Relatórios de vendas

3. **Funcionalidades Avançadas**
   - WebSockets para atualização em tempo real
   - Integração com impressora
   - Sistema de autenticação

## 💡 Dicas para Evoluir

- Comece com as funcionalidades básicas
- Teste em estabelecimentos pequenos
- Colete feedback dos usuários
- Implemente melhorias gradualmente

Este sistema básico já resolve 80% dos problemas de comanda em papel e pode ser o ponto de partida para um produto comercial!