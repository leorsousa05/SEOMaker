# Proposal: Public Frontend Redesign and Sleek Theme Styles

## 📌 Contexto & Motivação (WHY)
O design público atual do SEOMaker é muito simples e básico, assemelhando-se a um protótipo sem polimento visual. Para transformá-lo em um produto premium pronto para uso imediato (out-of-the-box), o design do site público precisa ser completamente reformulado.
A nova estética seguirá padrões modernos inspirados no estilo minimalista/SaaS (Vercel-like), com um sistema de cores em tons de cinza escuro e acentos vibrantes, suporte robusto para modos escuro/claro baseados na preferência do sistema, efeitos de glassmorphism em cards, animações suaves e tipografia moderna do Google Fonts (Outfit e Inter).

---

## 🎯 Escopo Funcional (WHAT)

### 1. Sistema de Design e Variáveis HSL
- Reescrever os estilos em `public/assets/style.css` usando variáveis HSL modernas.
- Definição de cores secundárias, acentos e bordas para o tema claro e tema escuro (usando `@media (prefers-color-scheme: dark)`).
- Importar e aplicar as fontes Google Fonts **Outfit** (para títulos `h1, h2, h3`) e **Inter** (para textos de corpo e botões).

### 2. Cabeçalho Nav e Rodapé Premium
- **Header:** Sticky navbar com desfoque de fundo (`backdrop-filter: blur`), logo em gradiente e links com micro-interações snappies (sublinhados fluidos no hover). Corrigir links dinâmicos do menu para apontar direto para `/{slug}` em vez de `/page/{slug}`.
- **Footer:** Grid de 3 colunas contendo informações da empresa/contato dinâmicos (telefone, e-mail), links de navegação rápidos e copyright estilizado.

### 3. Redesenho da Página Inicial (Templates e Seções Estáticas)
- **Hero Section:** Gradientes em texto (`background-clip: text`), espaçamentos amplos, animações sutis de entrada e botões de chamada de ação (`btn-primary` com hover iluminado/neon).
- **Features Section:** Layout de grid com cards flutuantes utilizando glassmorphism (sombras suaves, bordas semitransparentes).
- **Testimonials Section (Novo bloco visual):** Adicionar estrutura de depoimentos premium com avatares e blocos de citação minimalistas no template da home.
- **Formulário de Contato:** Totalmente repensado com inputs flutuantes e foco iluminado (glow acentuado).

---

## 🚫 Fora de Escopo (Out of Scope)
- Ferramenta administrativa para alterar a paleta de cores. As cores serão fixadas no arquivo CSS por padrão.
- Painel para editar depoimentos individualmente. Eles virão pré-populados no template ou no editor de blocos.
