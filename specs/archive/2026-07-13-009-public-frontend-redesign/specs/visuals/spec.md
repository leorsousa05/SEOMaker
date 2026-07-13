# Spec: Public Tailwind Visual Redesign Spec Delta

## ADDED

### public/assets/tailwind-input.css
- Arquivo de entrada do compilador do Tailwind CSS v4, estendendo as fontes `Outfit`/`Inter` e o tema de cores reativo HSL na diretiva `@theme`.

### package.json
- Inicializa dependências NPM e adiciona scripts de compilação CLI (`build:css`, `watch:css`).

### templates/public/home.php (Testimonials Section)
- Adiciona a seção de depoimentos estilizada com Tailwind.

---

## MODIFIED

### templates/public/layout.php
- Importa as fontes do Google Fonts no `<head>`.
- Navbar, menu dinâmico direto (`/{slug}`) e rodapé em 3 colunas totalmente convertidos para utilitários do Tailwind CSS.

### public/assets/style.css
- Sobrescrito com o CSS final compilado e minificado gerado pelo `@tailwindcss/cli`.

### templates/public/home.php
- Seções Hero, Features e formulário de contato reescritas com as classes utilitárias do Tailwind CSS.
