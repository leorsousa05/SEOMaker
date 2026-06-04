# Spec: Admin UI Redesign

## ADDED/MODIFIED

### Design System CSS
Ver design.md para variáveis, cores, tipografia, sombras, radius.

### templates/admin/layout.php
```html
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?> — <?= siteTitle ?></title>
  <link rel="stylesheet" href="/assets/admin-redesign.css">
</head>
<body class="admin">
  <!-- Mobile overlay -->
  <div class="sidebar-overlay" data-sidebar-overlay></div>
  
  <!-- Sidebar -->
  <aside class="sidebar" data-sidebar>
    <div class="sidebar-brand">
      <span class="brand-icon">LOGO</span>
      <span class="brand-text">SEO Panel</span>
    </div>
    <nav class="sidebar-nav">
      <a href="/admin" class="nav-item <?= active('dashboard') ?>">
        <svg class="nav-icon">...</svg>
        <span>Dashboard</span>
      </a>
      <a href="/admin/pages" class="nav-item <?= active('pages') ?>">
        <svg class="nav-icon">...</svg>
        <span>Páginas</span>
      </a>
      <a href="/admin/media" class="nav-item <?= active('media') ?>">
        <svg class="nav-icon">...</svg>
        <span>Galeria</span>
      </a>
      <a href="/admin/settings" class="nav-item <?= active('settings') ?>">
        <svg class="nav-icon">...</svg>
        <span>Configurações</span>
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="/admin/logout" class="nav-item">
        <svg class="nav-icon">...</svg>
        <span>Sair</span>
      </a>
    </div>
  </aside>
  
  <!-- Main -->
  <div class="main-wrapper">
    <header class="main-header">
      <button class="header-menu-toggle" data-menu-toggle>
        <svg>...</svg>
      </button>
      <div class="header-breadcrumb"><?= $breadcrumb ?></div>
      <div class="header-actions">
        <button class="header-theme-toggle" data-theme-toggle>
          <svg class="icon-sun">...</svg>
          <svg class="icon-moon">...</svg>
        </button>
      </div>
    </header>
    <main class="main-content">
      <?= $content ?>
    </main>
  </div>
  
  <!-- Toast container -->
  <div class="toast-container" data-toasts></div>
  
  <!-- Modal container -->
  <div class="modal-container" data-modals></div>
  
  <script src="/assets/admin.js"></script>
</body>
</html>
```

### public/assets/admin-redesign.css
- ~800 linhas
- Variáveis CSS com dark mode
- Componentes: card, button, table, form, tabs, toast, modal, sidebar, header
- Animações: fade, slide, scale
- Responsivo: sidebar colapsa, grids adaptam

### public/assets/admin.js
- Theme toggle: localStorage + data-theme
- Sidebar: toggle mobile, remember state
- Dropdowns: click outside to close
- Toasts: `window.toast(type, message)`
- Modals: `window.modal(options)` promise-based
- Table: select all checkbox
- Forms: unsaved warning

### Componentes SVG Inline
Icons (20x20, stroke 2):
- dashboard, file-text, image, settings, log-out, menu, sun, moon, search, filter, plus, edit, trash-2, eye, check, x, chevron-down, chevron-right, external-link, copy, arrow-left, bar-chart-2, users, mail, map-pin, phone, clock, dollar-sign, upload, image-plus, grip-vertical

### templates/admin/dashboard.php
- 4 metric cards (grid)
- CSS-only bar chart placeholder
- Recent activity list

### templates/admin/pages.php
- Header com título + btn "Nova Página"
- Search bar
- Filter tabs: Todos | Publicadas | Rascunhos
- Table: checkbox, título, slug, status badge, data, ações dropdown
- Pagination
- Empty state

### templates/admin/pages_edit.php
- Tabs: Conteúdo | SEO | Endereço
- Conteúdo: block editor
- SEO: meta fields + preview Google
- Endereço: address form
- Sidebar direita: status, slug, salvar

### templates/admin/media.php
- Upload zone drag & drop
- Grid de imagens
- Seleção múltipla
- Paginação

### templates/admin/settings.php
- Tabs verticais com ícones
- Form com cards por seção
- Sticky save bar

### templates/admin/login.php
- Centered card com logo
- Ilustração SVG placeholder
- Form clean
