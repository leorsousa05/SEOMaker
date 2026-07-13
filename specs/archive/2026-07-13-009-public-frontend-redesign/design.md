# Design: Public Frontend Redesign with Tailwind CSS v4

Este documento descreve as decisões técnicas para a migração do portal público para o Tailwind CSS v4 compilado localmente via CLI.

---

## 🛠️ Arquitetura de Compilação do CSS

Utilizaremos o compilador nativo do **Tailwind CSS v4** (`@tailwindcss/cli`) para processar e gerar o CSS otimizado de produção.

### 1. Estrutura de Arquivos
- **Input CSS:** `public/assets/tailwind-input.css` (onde importamos o Tailwind e estendemos os temas em CSS puro).
- **Output CSS:** `public/assets/style.css` (o arquivo final compilado, que continuará sendo carregado nos templates).
- **Ferramental:** `package.json` no diretório raiz do projeto para gerenciar scripts e dependências de desenvolvimento.

### 2. Extensão do Tema (tailwind-input.css)
O Tailwind v4 utiliza variáveis CSS nativas para configuração de temas. O arquivo de entrada conterá:

```css
@import "tailwindcss";

@theme {
  --color-bg-primary-light: hsl(0 0% 100%);
  --color-bg-surface-light: hsl(240 5% 98%);
  --color-bg-elevated-light: hsl(0 0% 100%);
  --color-text-primary-light: hsl(240 10% 4%);
  --color-text-secondary-light: hsl(240 5% 35%);
  --color-text-muted-light: hsl(240 5% 55%);
  --color-border-light: hsl(240 6% 90%);

  --color-bg-primary-dark: hsl(240 10% 4%);
  --color-bg-surface-dark: hsl(240 10% 6%);
  --color-bg-elevated-dark: hsl(240 10% 10%);
  --color-text-primary-dark: hsl(0 0% 98%);
  --color-text-secondary-dark: hsl(240 5% 65%);
  --color-text-muted-dark: hsl(240 5% 45%);
  --color-border-dark: hsl(240 6% 15%);

  --color-primary: hsl(262 83% 62%);
  --color-primary-foreground: hsl(0 0% 100%);
  --color-success: hsl(142 72% 29%);
  --color-error: hsl(0 84% 60%);

  --font-title: "Outfit", sans-serif;
  --font-body: "Inter", sans-serif;

  --animate-scale-press: scale-press 0.1s ease-out;
}

@keyframes scale-press {
  0% { transform: scale(1); }
  100% { transform: scale(0.97); }
}
```

---

## 🏗️ [Padrões Aplicados]
1. **Zero-Config CSS compilation:**
   Aproveitamento do design engine do Tailwind v4 que elimina o arquivo `tailwind.config.js` e compila todo o tema a partir da diretiva `@theme` declarada no próprio arquivo CSS de entrada.
2. **Fluid Backdrop Blurs:**
   Aproveitamento de utilitários como `backdrop-blur-md` e bordas semitransparentes com opacidade (`border-white/10` ou `border-black/5`) para o efeito de vidro translúcido (glassmorphism) nos cards de depoimento e no header flutuante.
3. **Adaptive Light/Dark Utility Classing:**
   Utilização do prefixo `dark:` nativo do Tailwind para reatividade de esquemas de cores de acordo com a preferência do sistema operacional (`@media (prefers-color-scheme: dark)`).

---

## 📐 Especificações de Classes de Componentes em Tailwind

### 1. Barra de Navegação (`layout.php`)
- **Container:** `sticky top-0 z-50 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-zinc-200/50 dark:border-zinc-800/50 py-4`
- **Logo Brand:** `text-2xl font-black font-title tracking-tight bg-gradient-to-r from-violet-600 to-blue-500 bg-clip-text text-transparent`
- **Links:** `text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors`

### 2. Seção Hero (`home.php`)
- **Título principal:** `text-4xl md:text-6xl font-black font-title tracking-tighter text-zinc-900 dark:text-white bg-gradient-to-r from-zinc-900 via-zinc-700 to-violet-600 dark:from-white dark:via-zinc-300 dark:to-violet-500 bg-clip-text text-transparent leading-none`
- **Botões:**
  - *Primário:* `bg-violet-600 hover:bg-violet-500 hover:shadow-[0_0_20px_rgba(124,58,237,0.3)] text-white font-semibold py-3 px-6 rounded-lg transition-all active:scale-97`
  - *Outline:* `border border-zinc-200 dark:border-zinc-800 text-zinc-900 dark:text-white hover:bg-zinc-50 dark:hover:bg-zinc-900 font-semibold py-3 px-6 rounded-lg transition-all`

### 3. Cards Bento Grid
- **Grid:** `grid grid-cols-1 md:grid-cols-3 gap-6`
- **Cards de Recursos:** `bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200/50 dark:border-zinc-800/50 rounded-xl p-8 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300`

### 4. Cards de Depoimento
- **Card:** `bg-white dark:bg-zinc-900/30 border border-zinc-200/50 dark:border-zinc-800/50 rounded-xl p-6 shadow-sm flex flex-col justify-between`
- **Avatar:** `w-11 h-11 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 flex items-center justify-center font-bold text-sm`
