# Tasks: 004-admin-complete

## Specs
- [x] Criar estrutura de spec
- [x] Criar proposal.md
- [x] Criar design.md
- [x] Criar spec deltas
- [x] Criar tasks.md

## Database
- [ ] Criar tabela media
- [ ] Criar tabela addresses
- [ ] Modificar tabela pages (content_blocks, address_id)

## Backend
- [ ] Models\Media
- [ ] Models\Address
- [ ] Content\BlockEditor
- [ ] Content\MediaManager
- [ ] Seo\LocalBusinessSchema
- [ ] Admin\MediaController
- [ ] Admin\PagesController (address, pagination, search)

## Design System
- [ ] public/assets/admin-redesign.css (~800 linhas)

## JavaScript
- [ ] public/assets/admin.js (theme, sidebar, toasts, modals, dropdowns)
- [ ] public/assets/block-editor.js (blocos, toolbar, reorder)
- [ ] public/assets/media.js (upload, drag-drop, select)

## Templates
- [ ] templates/admin/layout.php (redesign completo)
- [ ] templates/admin/login.php
- [ ] templates/admin/dashboard.php
- [ ] templates/admin/pages.php (search, filters, pagination)
- [ ] templates/admin/pages_edit.php (tabs, block editor, address, SEO)
- [ ] templates/admin/media.php
- [ ] templates/admin/settings.php (tabs verticais)
- [ ] templates/admin/_seo_preview.php
- [ ] templates/admin/_address_form.php
- [ ] templates/admin/_block_editor.php
- [ ] templates/admin/_media_modal.php
- [ ] templates/admin/_toast.php
- [ ] templates/admin/_modal.php

## Frontend Public
- [ ] templates/public/page.php (renderizar blocos)
- [ ] public/assets/style.css (blocos de conteúdo)

## Tests
- [ ] BlockEditor::render para cada tipo de bloco
- [ ] MediaManager upload/delete
- [ ] LocalBusinessSchema com address
- [ ] Admin.js theme toggle
- [ ] Verificar rotas

## Finalização
- [ ] Commit
