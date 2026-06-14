# Spec: Admin Panel

## ADDED

### Auth
- Session-based auth
- Login com username/password
- Logout
- Middleware `requireAuth()` protege rotas admin

### Dashboard
- View com estatísticas (total de páginas, ativas, última atualização)
- Cards de estatísticas com ícones e identidade visual da marca
- Menu lateral

### Settings
- Form para editar todas as configurações do site
- Validação básica

### Pages CRUD
- Listar páginas (slug, title, is_active)
- Criar/editar página
  - title, slug, meta_title, meta_description
  - content_html (textarea)
  - schema_type (select)
  - schema_data (textarea JSON)
  - is_active (checkbox)
- Deletar página

### Database Seeder
- Cria usuário admin padrão (admin / admin123 — deve ser alterado)
- Cria landing page inicial
- Popula settings defaults

## DESIGN

- Identidade visual alinhada ao site público (logo SVG, tipografia Syne + Inter)
- Paleta verde/tech: emerald como cor de destaque principal
- Modo escuro via `data-theme` + `localStorage` + `prefers-color-scheme`
- Focus states verdes e botões com gradiente de marca
