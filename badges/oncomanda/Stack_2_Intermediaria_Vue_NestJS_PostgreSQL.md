# ⚙️ Stack 2 – Intermediária (Vue + NestJS + PostgreSQL)

## 🎯 Visão Geral
Stack voltada para quem já tem alguma experiência e deseja criar um MVP mais robusto, modular e escalável, mantendo ainda a simplicidade do conceito “Menos é Mais”.

## 🧩 Tecnologias
- **Frontend:** Vue 3 + Vite + Pinia  
- **Backend:** NestJS (arquitetura modular)  
- **Banco de Dados:** PostgreSQL (TypeORM)  
- **Autenticação:** JWT + Refresh Token  
- **Comunicação em tempo real:** WebSocket Gateway (NestJS)  
- **Hospedagem:** Docker + Railway ou Render

## 🚀 Estrutura
````

/frontend  → Interface do garçom e monitor
/backend   → API REST modular + WS
/admin     → Dashboard administrativo

````

## 🗄️ Banco de Dados
PostgreSQL estruturado com entidades e migrações automáticas:
```bash
npm run typeorm migration:generate
npm run typeorm migration:run
````

## 🔌 Endpoints Principais

* POST /auth/login
* GET /tables
* POST /orders
* PATCH /orders/:id/status
* GET /reports/sales

## ⚡ Comunicação em Tempo Real

Utiliza **Gateway WebSocket** do NestJS para sincronização instantânea entre garçom, cozinha e bar.

## 🧰 Deploy Simplificado

* Configurar `.env` com variáveis de ambiente
* Rodar com Docker Compose (`docker-compose up`)

## 🧠 Ideal Para

Desenvolvedores intermediários que querem boas práticas, arquitetura limpa e fácil evolução do projeto.

````

---