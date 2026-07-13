# Design: Remove Admin Button and Rename Site to SEOMaker

Este documento especifica a remoção do botão "Painel" do cabeçalho público e o renomeamento das marcas legadas de "SEO Template" / "Test Site" para "SEOMaker".

---

## 🏗️ Modificações no Layout e Views

### 1. Remoção do Botão no Header
No arquivo `templates/public/layout.php`, o botão que aponta para `/admin` (`Painel`) será removido da barra de navegação principal.

### 2. Renomeação para SEOMaker
- **Templates de visualização pública:** Alterar o fallback padrão de `SEO Template` para `SEOMaker`.
- **Templates do Painel Administrativo:**
  - Alterar o título principal na aba de navegação e telas de login.
  - Substituir o logotipo textual na barra lateral.
- **Sementes do Banco de Dados (Seeder.php):** Alterar os valores padrões do banco SQLite criados no setup inicial para refletir `SEOMaker`.
- **Testes PHP:** Atualizar a configuração mockada nos testes que referenciavam `Test Site`.
