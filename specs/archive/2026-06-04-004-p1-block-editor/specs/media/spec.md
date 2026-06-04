# Spec: Galeria de Mídia

## ADDED

### Models\Media
```php
class Media {
    public ?int $id;
    public string $filename;
    public string $original_name;
    public string $mime_type;
    public int $size_bytes;
    public ?int $width;
    public ?int $height;
    public string $path;
    public ?string $created_at;
}
```

### src/Content/MediaManager.php
- `upload(array $files): array` — valida, move, salva no DB
- `delete(int $id): bool`
- `list(int $page = 1, int $perPage = 24): array` — paginação
- `find(int $id): ?Media`
- `generateThumbnail(string $path): ?string`

### Validação
- Tipos permitidos: image/jpeg, image/png, image/gif, image/webp
- Tamanho máximo: 5MB
- Nome sanitizado: `uniqid() . '_' . sanitize_filename`
- Pasta: `public/uploads/YYYY/MM/`

### src/Admin/MediaController.php
- `index()`: lista galeria
- `upload()`: recebe POST multipart, retorna JSON
- `delete(int $id)`: remove arquivo + DB

### templates/admin/media.php
- Grid de imagens (4-6 colunas)
- Upload zone drag & drop
- Preview com thumbnail
- Checkbox seleção múltipla
- Botão delete em lote
- Busca por nome
- Paginação

### templates/admin/_media_modal.php
- Modal para seleção de imagem no block editor
- Grid de imagens
- Upload inline
- Botão "Selecionar"

### Rotas
- GET /admin/media
- POST /admin/media/upload
- GET /admin/media/delete/{id}
- GET /admin/media/json (para carregar no modal)

### public/assets/media.js
- Drag & drop upload
- Preview antes de enviar
- Progresso de upload (simulado ou real)
- Seleção múltipla
- Delete com confirm modal
