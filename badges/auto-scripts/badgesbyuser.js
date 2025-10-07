/**
 * Script para gerar arquivos de contribuição de badge + atualizar README geral
 * Comunidade Código Certo Coders
 *
 * Uso:
 *  node gerarBadge.js --user=usuarioGitHub --nome="Nome Completo" --repo="linkRepo" --video="linkVideo"
 */

const fs = require('fs');
const path = require('path');

// --- Captura dos argumentos CLI ---
const args = process.argv.slice(2).reduce((acc, arg) => {
  const [key, value] = arg.split('=');
  acc[key.replace('--', '')] = value;
  return acc;
}, {});

// --- Validação ---
if (!args.user || !args.nome || !args.repo || !args.video) {
  console.error("❌ Erro: Informe --user, --nome, --repo e --video.");
  process.exit(1);
}

// --- Caminhos ---
const contributionsDir = path.join(__dirname, 'badges', 'contributions');
const generalReadmePath = path.join(__dirname, 'badges', 'README.md');
const fileName = `${args.user}-badge.oncomanda.md`;
const filePath = path.join(contributionsDir, fileName);

// --- Template do arquivo individual ---
const contributionContent = `# 🏅 Badge OnComanda Developer

Arquivo de contribuição referente ao desafio **OnComanda** concluído por este membro da comunidade **Código Certo Coders**.  

---

## 📛 Identificação do Membro
- **Usuário GitHub:** \`${args.user}\`  
- **Nome:** ${args.nome}  
- **Projeto entregue:** [Repositório OnComanda](${args.repo})  
- **Vídeo de demonstração:** [Assistir vídeo](${args.video})  

---

## 🎯 Critérios Atendidos
- [x] Repositório público no GitHub chamado \`OnComanda\` com frontend, monitor e admin.  
- [x] README contendo instruções de instalação e uso.  
- [x] Vídeo de até 5 minutos mostrando o sistema rodando e seus fluxos principais.  
- [x] Demonstração clara de funcionalidades: abrir comanda, lançar pedido, monitorar preparo e relatório simples.  

---

## 🧾 Validação da Equipe
Este membro demonstrou capacidade de:  
1. **Construir um sistema funcional e vendável** em WEB.  
2. Aplicar boas práticas de desenvolvimento e documentação.  
3. Entregar um produto real que resolve problemas de bares e restaurantes.  

---

## 🖼️ Badge Recebido
![Badge OnComanda](../oncomanda_badge.svg)

---

## 🎖️ Reconhecimento
O membro \`${args.user}\` está oficialmente certificado como **OnComanda Developer** dentro da Comunidade **Código Certo Coders**.  

---
✨ _Comunidade Código Certo Coders – Reconhecendo quem transforma conhecimento em resultado real._
`;

// --- Criação da pasta se não existir ---
fs.mkdirSync(contributionsDir, { recursive: true });

// --- Escrita do arquivo individual ---
fs.writeFileSync(filePath, contributionContent, 'utf-8');
console.log(`✅ Arquivo de contribuição criado: ${filePath}`);

// --- Atualizar README geral ---
let readmeContent = '';
if (fs.existsSync(generalReadmePath)) {
  readmeContent = fs.readFileSync(generalReadmePath, 'utf-8');
} else {
  readmeContent = `# 🏅 Contribuições - Badge OnComanda Developer

Lista oficial de membros da Comunidade **Código Certo Coders** que concluíram o **Desafio OnComanda**.  

| Usuário GitHub | Nome | Repositório | Vídeo | Badge |
|----------------|------|-------------|-------|-------|
`;
}

// --- Linha do novo participante ---
const newLine = `| [${args.user}](https://github.com/${args.user}) | ${args.nome} | [Repositório](${args.repo}) | [Vídeo](${args.video}) | ![Badge](./oncomanda_badge.svg) |`;

// --- Verifica se já existe ---
if (!readmeContent.includes(args.user)) {
  readmeContent += `\n${newLine}`;
  fs.writeFileSync(generalReadmePath, readmeContent, 'utf-8');
  console.log(`✅ README geral atualizado em: ${generalReadmePath}`);
} else {
  console.log("ℹ️ Usuário já consta no README geral, nenhuma atualização feita.");
}
/*
📌 **O que esse script faz agora:**

1. Cria automaticamente o arquivo de contribuição individual em `badges/contributions/`.
2. Atualiza (ou cria se não existir) o `badges/README.md` com uma tabela geral listando todos os membros que concluíram o **Desafio OnComanda**.
3. Garante que um usuário não seja duplicado no README.

Exemplo de execução:

```bash
node gerarBadge.js --user=joaosilva --nome="João da Silva" --repo="https://github.com/joaosilva/OnComanda" --video="https://youtu.be/xxxxx"
```

Resultado esperado no `badges/README.md`:

| Usuário GitHub                            | Nome          | Repositório                                           | Vídeo                           | Badge                           |
| ----------------------------------------- | ------------- | ----------------------------------------------------- | ------------------------------- | ------------------------------- |
| [joaosilva](https://github.com/joaosilva) | João da Silva | [Repositório](https://github.com/joaosilva/OnComanda) | [Vídeo](https://youtu.be/xxxxx) | ![Badge](./oncomanda_badge.svg) |

---
*/