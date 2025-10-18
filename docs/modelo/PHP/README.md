# OnComanda - Sistema de Comandas Eletrônicas (PHP)

Sistema modular para gerenciamento de comandas eletrônicas para restaurantes, desenvolvido em PHP puro para iniciantes.

## Estrutura do Projeto

O projeto segue uma estrutura modular simples, organizada da seguinte forma:

```
PHP/
├── config/             # Configurações do sistema
│   ├── database.php    # Conexão com o banco de dados
│   └── helpers.php     # Funções auxiliares
├── models/             # Modelos de dados
│   ├── Item.php        # Modelo para itens do cardápio
│   ├── Mesa.php        # Modelo para mesas
│   └── Pedido.php      # Modelo para pedidos
├── controllers/        # Controladores
│   ├── CardapioController.php
│   ├── MesaController.php
│   └── PedidoController.php
├── views/              # Arquivos de visualização
│   └── index.html      # Página principal
├── public/             # Arquivos públicos
│   ├── index.php       # Ponto de entrada da aplicação
│   ├── css/            # Estilos CSS
│   └── js/             # Scripts JavaScript
└── database/           # Banco de dados SQLite
    └── app.db          # Arquivo do banco de dados
```

## Requisitos

- PHP 7.4 ou superior
- Extensão SQLite3 para PHP
- Servidor web (Apache, Nginx) ou servidor embutido do PHP

## Instalação

1. Clone o repositório ou baixe os arquivos
2. Certifique-se de que o PHP está instalado com a extensão SQLite3
3. Configure as permissões para que o diretório `database` seja gravável

## Executando o Projeto

Você pode executar o projeto usando o servidor embutido do PHP:

```bash
cd caminho/para/pasta/public
php -S localhost:8000
```

Acesse o sistema em: http://localhost:8000

## Funcionalidades

O sistema permite:

- Gerenciamento de mesas (adicionar, atualizar status)
- Gerenciamento do cardápio (adicionar itens)
- Criação e gerenciamento de pedidos
- Visualização de pedidos por status

## Estrutura de Código

### Modelos

Os modelos representam as entidades do sistema e encapsulam a lógica de acesso ao banco de dados:

- **Item**: Gerencia itens do cardápio
- **Mesa**: Gerencia mesas do restaurante
- **Pedido**: Gerencia pedidos

### Controladores

Os controladores processam as requisições e coordenam a interação entre os modelos e as visualizações:

- **CardapioController**: Gerencia operações relacionadas ao cardápio
- **MesaController**: Gerencia operações relacionadas às mesas
- **PedidoController**: Gerencia operações relacionadas aos pedidos

### API REST

O sistema implementa uma API REST simples com os seguintes endpoints:

- `/api/cardapio` - GET (listar itens), POST (adicionar item)
- `/api/mesa` - GET (listar mesas), POST (adicionar mesa)
- `/api/mesa/{id}` - PUT (atualizar status)
- `/api/pedido` - GET (listar pedidos), POST (criar pedido)
- `/api/pedido/{id}` - PUT (atualizar status)

## Frontend

O frontend é construído com HTML, CSS e JavaScript puro, sem dependências externas. A interface é responsiva e permite:

- Visualizar e gerenciar mesas
- Visualizar e adicionar itens ao cardápio
- Criar pedidos
- Visualizar e atualizar status de pedidos

## Desenvolvimento Futuro

Possíveis melhorias para o projeto:

1. Implementação de autenticação de usuários
2. Relatórios de vendas
3. Impressão de comandas
4. Integração com sistemas de pagamento
5. Migração para um framework PHP como Laravel ou Symfony

## Conceito "Menos é Mais"

Este projeto foi desenvolvido seguindo o conceito "Menos é Mais", focando em:

- Código limpo e legível
- Estrutura modular simples
- Mínimo de dependências externas
- Fácil entendimento para desenvolvedores iniciantes
- Funcionalidades essenciais implementadas de forma direta

O objetivo é proporcionar uma base sólida para aprendizado, permitindo que desenvolvedores iniciantes compreendam os conceitos fundamentais de desenvolvimento web com PHP antes de migrarem para frameworks mais complexos.