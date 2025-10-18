# OnComanda - Comanda Eletrônica Web

## Descrição do Projeto
OnComanda é um sistema de comanda eletrônica web para bares e restaurantes que resolve problemas reais:
- Agiliza o processo de pedidos
- Evita erros em cálculos
- Permite visão rápida dos pedidos em andamento
- Facilita o gerenciamento de mesas e clientes

## Versão Básica (MVP)

### Configuração do Ambiente
- Instale Python (versão 3.12+)
- Crie um ambiente virtual:
  ```
  python -m venv venv
  venv\Scripts\activate  # Windows
  source venv/bin/activate  # Mac/Linux
  ```
- Instale os pacotes necessários:
  ```
  pip install flask gunicorn flask-sqlalchemy
  ```
- Crie um arquivo `requirements.txt`:
  ```
  flask
  gunicorn
  flask-sqlalchemy
  ```

### Estrutura do Projeto (MVP)
```
oncomanda/
├── app.py  # Aplicação principal
├── static/
│   ├── index.html
│   ├── style.css
│   └── script.js
├── requirements.txt
└── venv/
```

### Backend com Flask e SQLite
O backend utiliza Flask para criar uma API RESTful e SQLite para armazenar dados localmente.

```python
from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__, static_folder='static')
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///app.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)

# Modelos do banco
class Item(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(80), nullable=False)
    preco = db.Column(db.Float, nullable=False)

class Pedido(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    mesa = db.Column(db.Integer, nullable=False)
    itens = db.Column(db.String(200), nullable=False)  # JSON simples como string: {"cerveja": 2}
    subtotal = db.Column(db.Float, nullable=False)
    imposto = db.Column(db.Float, nullable=False)
    total = db.Column(db.Float, nullable=False)
    status = db.Column(db.String(20), nullable=False, default='aberto')

# Imposto fixo (10%)
IMPOSTO = 0.10

# Cria o banco na primeira execução
with app.app_context():
    db.create_all()
```

### Endpoints da API (MVP)
- `/api/cardapio` (GET/POST): Gerencia o cardápio
- `/api/pedido` (POST): Cria novos pedidos
- `/api/pedidos` (GET): Lista todos os pedidos
- `/api/pedido/<id>/fechar` (POST): Fecha um pedido específico

### Frontend Simples
Interface básica com HTML, CSS e JavaScript vanilla para:
- Adicionar itens ao cardápio
- Criar pedidos por mesa
- Visualizar pedidos abertos
- Fechar pedidos

### Deploy
O projeto pode ser facilmente implantado no Render.com:
- Crie uma conta gratuita no Render
- Configure um novo Web Service conectado ao repositório GitHub
- Defina as configurações:
  - Runtime: Python
  - Build Command: `pip install -r requirements.txt`
  - Start Command: `gunicorn app:app`
  - Adicione um Disco Persistente para o banco de dados

## Versão Modular (Avançada)

### Estrutura do Projeto (Modular)
```
oncomanda/
├── app.py  # Entry point
├── blueprints/  # Blueprints Flask
│   ├── __init__.py
│   ├── auth.py  # Login/usuários
│   ├── admin.py  # Cadastros e relatórios
│   ├── garcom.py  # Mesas e pedidos
│   └── monitor.py  # Monitor preparos
├── models.py  # Modelos SQLAlchemy (separado)
├── static/
│   ├── index.html
│   ├── style.css
│   └── script.js  # JS organizado em objetos
├── requirements.txt
└── venv/
```

### Modelos Expandidos
A versão modular inclui modelos mais completos:
- `Usuario`: Gerenciamento de usuários com diferentes funções
- `Mesa`: Status de mesas e reservas
- `Item`: Produtos do cardápio com categorias
- `Pedido`: Pedidos com mais detalhes e rastreamento

### Blueprints para Organização
O código é dividido em módulos independentes:
- `auth.py`: Autenticação e gerenciamento de usuários
- `admin.py`: Cadastros e relatórios administrativos
- `garcom.py`: Gerenciamento de mesas e pedidos
- `monitor.py`: Monitoramento de preparos e entregas

### Frontend Modular
O JavaScript é organizado em "módulos" usando objetos:
- `Auth`: Funções de login e controle de acesso
- `Garcom`: Gerenciamento de mesas e pedidos
- `Monitor`: Acompanhamento de preparos
- `Admin`: Funções administrativas e relatórios

### Funcionalidades Adicionais
- Sistema de login e controle de acesso
- Gerenciamento de mesas (livre/ocupada/reservada)
- Categorização de itens do cardápio
- Monitoramento de tempo de preparo
- Relatórios de vendas
- Setores para diferentes áreas (cozinha, bar, etc.)

## Como Executar o Projeto

### Localmente
1. Clone o repositório
2. Configure o ambiente virtual e instale as dependências
3. Execute `python app.py`
4. Acesse `http://127.0.0.1:5000` no navegador

### Em Produção
1. Faça deploy no Render.com conforme instruções acima
2. Acesse a URL fornecida pelo Render

## Próximos Passos
- Implementação de autenticação JWT
- Interface de usuário mais elaborada
- Relatórios avançados
- Integração com impressoras térmicas
- Aplicativo móvel para garçons

---

Projeto desenvolvido para o desafio Código Certo Coders.