# OnComanda - Comanda Eletrônica Web (Versão PHP)

## Descrição do Projeto
OnComanda é um sistema de comanda eletrônica web para bares e restaurantes que resolve problemas reais:
- Agiliza o processo de pedidos
- Evita erros em cálculos
- Permite visão rápida dos pedidos em andamento
- Facilita o gerenciamento de mesas e clientes

## Versão Básica (MVP)

### Configuração do Ambiente
- Instale PHP (versão 7.4+)
- Instale um servidor web como Apache ou use o servidor embutido do PHP
- Certifique-se de ter o SQLite habilitado no PHP
- Para iniciar o servidor embutido do PHP:
  ```
  php -S localhost:8000
  ```

### Estrutura do Projeto (MVP)
```
oncomanda/
├── index.php  # Ponto de entrada da aplicação
├── api/
│   ├── config.php  # Configurações do banco de dados
│   ├── cardapio.php  # Endpoints para gerenciar cardápio
│   ├── pedido.php  # Endpoints para gerenciar pedidos
│   └── db.php  # Funções de conexão com o banco
├── static/
│   ├── index.html
│   ├── style.css
│   └── script.js
└── database/
    └── app.db  # Banco de dados SQLite
```

### Backend com PHP e SQLite
O backend utiliza PHP puro com SQLite para armazenar dados localmente.

#### config.php
```php
<?php
// Configurações do banco de dados
define('DB_PATH', __DIR__ . '/../database/app.db');
define('IMPOSTO', 0.10); // Imposto fixo (10%)

// Função para conectar ao banco de dados
function conectarDB() {
    $db = new SQLite3(DB_PATH);
    
    // Criar tabelas se não existirem
    $db->exec('
        CREATE TABLE IF NOT EXISTS item (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            preco REAL NOT NULL
        )
    ');
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS pedido (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            mesa INTEGER NOT NULL,
            itens TEXT NOT NULL,
            subtotal REAL NOT NULL,
            imposto REAL NOT NULL,
            total REAL NOT NULL,
            status TEXT NOT NULL DEFAULT "aberto"
        )
    ');
    
    return $db;
}

// Função para retornar resposta em JSON
function responderJSON($dados, $codigo = 200) {
    header('Content-Type: application/json');
    http_response_code($codigo);
    echo json_encode($dados);
    exit;
}
?>
```

#### cardapio.php
```php
<?php
require_once 'config.php';

// Roteamento básico
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    listarCardapio();
} elseif ($metodo === 'POST') {
    adicionarItem();
} else {
    responderJSON(['erro' => 'Método não permitido'], 405);
}

// Função para listar itens do cardápio
function listarCardapio() {
    $db = conectarDB();
    $resultado = $db->query('SELECT id, nome, preco FROM item');
    
    $itens = [];
    while ($item = $resultado->fetchArray(SQLITE3_ASSOC)) {
        $itens[] = $item;
    }
    
    responderJSON($itens);
}

// Função para adicionar item ao cardápio
function adicionarItem() {
    // Obter dados do corpo da requisição
    $dados = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($dados['nome']) || !isset($dados['preco'])) {
        responderJSON(['erro' => 'Nome e preço são obrigatórios'], 400);
    }
    
    $db = conectarDB();
    $stmt = $db->prepare('INSERT INTO item (nome, preco) VALUES (:nome, :preco)');
    $stmt->bindValue(':nome', $dados['nome'], SQLITE3_TEXT);
    $stmt->bindValue(':preco', $dados['preco'], SQLITE3_FLOAT);
    $stmt->execute();
    
    $id = $db->lastInsertRowID();
    responderJSON(['sucesso' => 'Item adicionado!', 'id' => $id], 201);
}
?>
```

#### pedido.php
```php
<?php
require_once 'config.php';

// Roteamento básico
$metodo = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Verificar se é uma operação em um pedido específico
if (isset($uri[3]) && $uri[3] === 'pedido' && isset($uri[4]) && is_numeric($uri[4])) {
    $pedidoId = (int) $uri[4];
    
    if (isset($uri[5]) && $uri[5] === 'fechar' && $metodo === 'POST') {
        fecharPedido($pedidoId);
        exit;
    }
}

// Rotas principais
if ($metodo === 'GET' && isset($uri[3]) && $uri[3] === 'pedidos') {
    listarPedidos();
} elseif ($metodo === 'POST' && isset($uri[3]) && $uri[3] === 'pedido') {
    criarPedido();
} else {
    responderJSON(['erro' => 'Rota não encontrada'], 404);
}

// Função para criar um novo pedido
function criarPedido() {
    $dados = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($dados['mesa']) || !isset($dados['itens']) || empty($dados['itens'])) {
        responderJSON(['erro' => 'Mesa e itens são obrigatórios'], 400);
    }
    
    $db = conectarDB();
    $subtotal = 0.0;
    
    // Calcular subtotal
    foreach ($dados['itens'] as $nome => $qtd) {
        $stmt = $db->prepare('SELECT preco FROM item WHERE nome = :nome');
        $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
        $resultado = $stmt->execute();
        $item = $resultado->fetchArray(SQLITE3_ASSOC);
        
        if ($item) {
            $subtotal += $item['preco'] * $qtd;
        } else {
            responderJSON(['erro' => "Item '$nome' não encontrado"], 400);
        }
    }
    
    $imposto = $subtotal * IMPOSTO;
    $total = $subtotal + $imposto;
    
    // Salvar pedido
    $stmt = $db->prepare('
        INSERT INTO pedido (mesa, itens, subtotal, imposto, total, status)
        VALUES (:mesa, :itens, :subtotal, :imposto, :total, "aberto")
    ');
    $stmt->bindValue(':mesa', $dados['mesa'], SQLITE3_INTEGER);
    $stmt->bindValue(':itens', json_encode($dados['itens']), SQLITE3_TEXT);
    $stmt->bindValue(':subtotal', $subtotal, SQLITE3_FLOAT);
    $stmt->bindValue(':imposto', $imposto, SQLITE3_FLOAT);
    $stmt->bindValue(':total', $total, SQLITE3_FLOAT);
    $stmt->execute();
    
    $id = $db->lastInsertRowID();
    responderJSON(['sucesso' => 'Pedido criado!', 'id' => $id, 'total' => $total], 201);
}

// Função para listar pedidos
function listarPedidos() {
    $db = conectarDB();
    $resultado = $db->query('SELECT id, mesa, itens, total, status FROM pedido');
    
    $pedidos = [];
    while ($pedido = $resultado->fetchArray(SQLITE3_ASSOC)) {
        $pedidos[] = $pedido;
    }
    
    responderJSON($pedidos);
}

// Função para fechar um pedido
function fecharPedido($pedidoId) {
    $db = conectarDB();
    
    // Verificar se o pedido existe
    $stmt = $db->prepare('SELECT id, status FROM pedido WHERE id = :id');
    $stmt->bindValue(':id', $pedidoId, SQLITE3_INTEGER);
    $resultado = $stmt->execute();
    $pedido = $resultado->fetchArray(SQLITE3_ASSOC);
    
    if (!$pedido) {
        responderJSON(['erro' => 'Pedido não encontrado'], 404);
    }
    
    if ($pedido['status'] === 'fechado') {
        responderJSON(['erro' => 'Pedido já está fechado'], 400);
    }
    
    // Fechar pedido
    $stmt = $db->prepare('UPDATE pedido SET status = "fechado" WHERE id = :id');
    $stmt->bindValue(':id', $pedidoId, SQLITE3_INTEGER);
    $stmt->execute();
    
    responderJSON(['sucesso' => 'Pedido fechado com sucesso!']);
}
?>
```

#### index.php (Ponto de entrada)
```php
<?php
// Roteamento simples para a API
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Verificar se é uma requisição para a API
if (strpos($uri, '/api/') === 0) {
    $rota = explode('/', $uri);
    
    if (isset($rota[2])) {
        switch ($rota[2]) {
            case 'cardapio':
                require_once 'api/cardapio.php';
                break;
            case 'pedido':
            case 'pedidos':
                require_once 'api/pedido.php';
                break;
            default:
                header('HTTP/1.1 404 Not Found');
                echo json_encode(['erro' => 'Rota não encontrada']);
                exit;
        }
    }
} else {
    // Servir o arquivo HTML principal
    include 'static/index.html';
}
?>
```

### Frontend Simples
Interface básica com HTML, CSS e JavaScript vanilla para:
- Adicionar itens ao cardápio
- Criar pedidos por mesa
- Visualizar pedidos abertos
- Fechar pedidos

O frontend é o mesmo da versão Python, apenas ajustando as chamadas de API para o formato PHP.

### Deploy
O projeto pode ser facilmente implantado em qualquer servidor compartilhado com suporte a PHP:
- Hospedagem compartilhada (ex: Hostgator, Locaweb)
- Servidores VPS com Apache/Nginx
- Plataformas como InfinityFree para testes gratuitos

## Versão Modular (Avançada)

Para uma versão mais modular, você pode organizar o código PHP em classes e usar um padrão MVC simples:

### Estrutura do Projeto (Modular)
```
oncomanda/
├── index.php  # Ponto de entrada
├── config/
│   └── database.php  # Configurações do banco
├── models/
│   ├── Item.php
│   ├── Pedido.php
│   ├── Mesa.php
│   └── Usuario.php
├── controllers/
│   ├── CardapioController.php
│   ├── PedidoController.php
│   ├── MesaController.php
│   └── UsuarioController.php
├── views/
│   ├── index.html
│   ├── admin/
│   ├── garcom/
│   └── monitor/
├── public/
│   ├── css/
│   ├── js/
│   └── img/
└── database/
    └── app.db
```

### Como Executar o Projeto

#### Localmente
1. Clone o repositório
2. Configure um servidor web (Apache/Nginx) ou use o servidor embutido do PHP:
   ```
   php -S localhost:8000
   ```
3. Acesse `http://localhost:8000` no navegador

#### Em Produção
1. Faça upload dos arquivos para seu servidor web
2. Configure as permissões corretas para a pasta `database` (777 ou equivalente)
3. Acesse a URL do seu servidor

## Próximos Passos
- Implementação de autenticação com sessões PHP
- Interface de usuário mais elaborada
- Relatórios avançados
- Integração com impressoras térmicas
- Migração para um framework PHP como Laravel ou Slim

---

Projeto desenvolvido para o desafio Código Certo Coders.