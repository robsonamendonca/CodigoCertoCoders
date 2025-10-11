# ☁️ Stack 3 – Serverless (Svelte + Firebase)

## 🎯 Visão Geral
Stack **sem servidor**, ideal para quem quer eliminar complexidade de backend, focando apenas em construir uma aplicação funcional hospedada com custo quase zero.

## 🧩 Tecnologias
- **Frontend:** SvelteKit  
- **Backend:** Firebase Cloud Functions  
- **Banco de Dados:** Firebase Realtime Database  
- **Autenticação:** Firebase Auth  
- **Hospedagem:** Firebase Hosting  

## 🚀 Estrutura
````

/frontend  → Interface do garçom, monitor e admin integradas

```

## 🗄️ Banco de Dados
Estrutura simples no Realtime Database:
```

users/{id}
tables/{id}
orders/{id}
orderItems/{id}
products/{id}

````

## 🔌 Funções e Endpoints
- `/auth/login` → handled by Firebase Auth  
- `/orders/create` → Cloud Function HTTPS  
- `/orders/updateStatus` → Cloud Function HTTPS  

## ⚡ Comunicação em Tempo Real
Firebase oferece atualização automática com listeners (`onValue`, `onUpdate`) sem precisar configurar WebSocket manualmente.

## 🧰 Deploy Simplificado
```bash
firebase init
npm run build
firebase deploy
````

## 🧠 Ideal Para

Alunos e iniciantes que querem **algo pronto e funcional**, sem se preocupar com infraestrutura ou servidores, apenas com lógica e experiência do usuário.

```
