# Spec: Admin Leigo-Friendly

## ADDED

### Seo\SchemaFormBuilder
- `fieldsForType(string $type): array` — retorna definição de campos
- `buildJson(array $postData, string $type): string` — converte POST → JSON
- `parseJson(string $json, string $type): array` — converte JSON → valores de campo

### public/assets/schema-editor.js
- Escuta change no select `schema_type`
- Renderiza campos dinamicamente baseado no tipo
- No submit, serializa campos → JSON e popula hidden `schema_data`
- Suporta nested keys (dot notation: `contactPoint.telephone`)

### templates/admin/_schema_fields.php
- Partial para renderizar campos de schema
- Inputs gerados dinamicamente via JS

### SEO Preview (templates/admin/_seo_preview.php)
- Card simulando SERP do Google
- Mostra title, URL, description
- Atualizado via JS ou PHP no load

## MODIFIED

### templates/admin/pages_edit.php
- Substituir textarea `schema_data` por container JS `#schema-fields`
- Adicionar hidden `schema_data` para compatibilidade
- Incluir `schema-editor.js`
- Incluir `_seo_preview.php` acima do form

### templates/admin/settings.php
- Adicionar helper texts em campos técnicos
- `site_url` com validação de URL
- `analytics_id` com placeholder explicativo

### src/Admin/PagesController.php
- No save: se `schema_type` e campos de schema presentes, usar SchemaFormBuilder
- No edit: parse schema_data e passar valores para view

### public/assets/admin.css
- Estilos para preview Google
- Estilos para campos dinâmicos de schema
- Tooltips/help text
