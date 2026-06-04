# Design: Template SEO Completo

## Architecture

```
seo-template/
├── public/
│   ├── uploads/              ← imagens enviadas
│   ├── assets/
│   │   ├── admin.css         ← design system completo
│   │   ├── admin.js          ← interações admin
│   │   ├── block-editor.js   ← editor de blocos
│   │   ├── schema-editor.js  ← editor de schema
│   │   └── tabs.js
│   ├── index.php             ← front controller
│   └── .htaccess
├── src/
│   ├── Core/                 ← router, db, config, view, mail, seeder
│   ├── Seo/                  ← SeoManager, SchemaGenerator, SitemapGenerator, SchemaFormBuilder
│   ├── Content/              ← NOVO: BlockEditor, MediaManager
│   ├── Admin/                ← controllers
│   └── Models/               ← Page, User, Setting, Media
├── templates/
│   ├── public/               ← site do cliente
│   └── admin/                ← painel admin
└── config/
    └── database.sqlite
```

## Data Model

### media (NOVO)
| id | filename | original_name | mime_type | size_bytes | width | height | path | created_at |

### pages (MODIFICADO)
| ... | content_blocks (TEXT JSON) | address_json (TEXT) | ... |

### addresses (NOVO)
| id | page_id | street | number | complement | neighborhood | city | state | zip | country | lat | lng |

## Block Editor JSON Format
```json
[
  {"type": "text", "content": "<p>Parágrafo...</p>"},
  {"type": "image", "media_id": 5, "alt": "Descrição", "caption": "Legenda"},
  {"type": "gallery", "media_ids": [1,2,3], "columns": 3},
  {"type": "video", "url": "https://youtube.com/...", "provider": "youtube"},
  {"type": "map", "address_id": 1, "zoom": 15},
  {"type": "cta", "text": "Fale Conosco", "url": "/contato", "style": "primary"},
  {"type": "faq", "items": [{"question": "...", "answer": "..."}]},
  {"type": "spacer", "height": 40}
]
```

## Media Upload Flow
1. Usuário arrasta ou seleciona imagens
2. PHP recebe $_FILES, valida tipo/tamanho
3. Move para `public/uploads/YYYY/MM/filename_unique.ext`
4. Gera thumbnail (GD) se disponível
5. Salva metadados na tabela media
6. Retorna JSON com dados da imagem

## Address / Geolocation Flow
1. Admin preenche campos de endereço na página
2. Sistema gera schema LocalBusiness com address structured
3. Opcional: geocoding via API externa (Nominatim/OpenCage) — pode ser adicionado depois
4. Frontend renderiza mapa embed do Google Maps com o endereço

## Admin Pages

### Dashboard
- Cards: total páginas, imagens, contatos, última atualização
- Gráfico placeholder (CSS)
- Atividade recente

### Páginas
- Tabela com busca, filtros (status), paginação
- Ações: editar, duplicar, deletar

### Editor de Página
- Tabs: Conteúdo | SEO | Endereço | Config
- Conteúdo: block editor visual
- SEO: meta tags + preview Google
- Endereço: campos estruturados
- Config: slug, status

### Galeria
- Grid de imagens com thumbnail
- Upload drag & drop
- Seleção múltipla + delete em lote
- Busca por nome

### Configurações
- Tabs: Geral | SEO | Contato | Email | Redes Sociais
- Help texts em todos os campos

## Routes

### Public (mesmas)
- GET /, /page/{slug}, /sitemap.xml, /robots.txt, POST /contact

### Admin
- GET|POST /admin/login, /admin/logout
- GET /admin → Dashboard
- GET|POST /admin/settings
- GET|POST /admin/pages, /admin/pages/edit/{id}, POST /admin/pages/save, GET /admin/pages/delete/{id}
- GET|POST /admin/media → Media gallery
- POST /admin/media/upload → Upload handler
- GET /admin/media/delete/{id}

## Design System
Ver `specs/ux/spec.md` para detalhes de design system, dark mode, componentes, animações.
