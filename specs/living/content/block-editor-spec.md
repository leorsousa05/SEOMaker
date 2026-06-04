# Spec: Block Editor Visual

## ADDED

### Content\BlockEditor
- `render(array $blocks): string` — converte blocos JSON → HTML
- `sanitizeHtml(string $html): string` — remove script, onclick, etc.
- `defaultBlocks(): array` — blocos iniciais para nova página

### Blocos Suportados

#### text
```json
{"type": "text", "content": "<p>Texto...</p>"}
```
Render: `<div class="block-text">$content</div>`

#### image
```json
{"type": "image", "media_id": 5, "alt": "...", "caption": "...", "align": "center"}
```
Render: `<figure class="block-image block-image--center"><img src="/uploads/..." alt="..."><figcaption>...</figcaption></figure>`

#### gallery
```json
{"type": "gallery", "media_ids": [1,2,3], "columns": 3, "gap": "medium"}
```
Render: `<div class="block-gallery block-gallery--3">...grid...</div>`

#### video
```json
{"type": "video", "url": "https://youtube.com/watch?v=XXX", "provider": "youtube"}
```
Render: `<div class="block-video"><iframe src="https://youtube.com/embed/XXX"></iframe></div>`
Extrai video ID de YouTube/Vimeo URLs.

#### map
```json
{"type": "map", "address_id": 1, "zoom": 15}
```
Render: `<iframe src="https://maps.google.com/maps?q=ADDRESS&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>`

#### cta
```json
{"type": "cta", "text": "Fale Conosco", "url": "/contato", "style": "primary"}
```
Render: `<a href="/contato" class="btn btn-primary btn-lg">Fale Conosco</a>`

#### faq
```json
{"type": "faq", "items": [{"question": "Q?", "answer": "A."}]}
```
Render: acordeão HTML com details/summary

#### spacer
```json
{"type": "spacer", "height": 40}
```
Render: `<div style="height: 40px"></div>`

### public/assets/block-editor.js
- Renderiza toolbar de blocos (+ Adicionar bloco)
- Cada bloco: toolbar própria (mover, duplicar, deletar)
- Texto: contenteditable com toolbar flutuante
- Imagem: abre galeria modal para selecionar
- Galeria: multi-select de imagens
- Vídeo: input de URL com preview
- Mapa: vincula ao address_id da página
- CTA: inputs de texto, URL, select de estilo
- FAQ: lista dinâmica de Q&A
- Spacer: slider de altura
- JSON output em hidden input `#content_blocks`

### templates/admin/_block_editor.php
- Container `#block-editor`
- Botão "+ Adicionar Bloco" com dropdown de tipos
- Área de blocos renderizados

### templates/admin/pages_edit.php
- Substituir textarea `content_html` por block editor
- Manter `content_html` como fallback (gerado no save)
