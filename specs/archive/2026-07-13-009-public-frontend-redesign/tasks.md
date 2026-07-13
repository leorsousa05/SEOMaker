# Tasks: 009-public-frontend-redesign

Checklist de tarefas para a migração e redesenho visual com Tailwind CSS v4.

## 📦 1. Configuração e Instalação (Tailwind CSS v4)
- [ ] Inicializar o Node.js no projeto com `npm init -y` caso não exista `package.json`.
- [ ] Instalar as dependências de compilação: `tailwindcss` e `@tailwindcss/cli`.
- [ ] Adicionar scripts no `package.json`:
  - `build:css`: `tailwindcss -i ./public/assets/tailwind-input.css -o ./public/assets/style.css --minify`
  - `watch:css`: `tailwindcss -i ./public/assets/tailwind-input.css -o ./public/assets/style.css --watch`

## 🎨 2. Arquivo de Entrada (Input CSS)
- [ ] Criar o arquivo `public/assets/tailwind-input.css` importando o Tailwind (`@import "tailwindcss";`).
- [ ] Configurar a diretiva `@theme` para registrar as fontes (`Outfit`, `Inter`) e cores HSL do sistema de design.

## 🧭 3. Cabeçalho Nav e Rodapé (`layout.php`)
- [ ] Adicionar pré-conexões e links de fontes no `<head>`.
- [ ] Converter a barra de navegação superior (`.navbar`) para Tailwind. Utilizar classes como `sticky top-0 z-50 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b`.
- [ ] Converter links dinâmicos do menu para apontar diretamente para `/{slug}` e estilizá-los com hover reativo.
- [ ] Redesenhar o rodapé em uma estrutura flexível de 3 colunas em Tailwind.

## 🏠 4. Template Principal (`home.php`)
- [ ] Redesenhar a seção Hero com classes do Tailwind (títulos grandes, gradiente de texto, botões de ação primário/outline reativos).
- [ ] Redesenhar o grid de Features utilizando as classes utilitárias de grid e cards glassmorphic.
- [ ] Implementar a seção de Depoimentos com a estrutura de grid e avatares estilizados do Tailwind.
- [ ] Estilizar o formulário de contato com cards flutuantes, inputs estilizados, labels em uppercase e focus glow.

## 📄 5. Páginas Dinâmicas e Blocos (`page.php`)
- [ ] Ajustar o layout do container em `page.php`.
- [ ] Adaptar a folha de estilos de entrada (`tailwind-input.css`) para estender e estilizar de forma elegante os blocos gerados pelo `BlockEditor` (FAQ, CTA, Spacer, etc.).

## 🧪 6. Compilação e Testes
- [ ] Rodar o build de produção (`npm run build:css`) para gerar o arquivo final compilado em `public/assets/style.css`.
- [ ] Executar a suíte de testes (`php tests/run.php`) para garantir a conformidade dos dados do banco e páginas.
