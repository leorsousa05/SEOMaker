# Spec: Galeria de Mídia

## ADDED

### src/Content/MediaManager.php
```php
class MediaManager {
    public static function upload(array $file): array;
    public static function delete(int $id): bool;
    public static function list(int $page = 1, int $perPage = 24): array;
    public static function find(int $id): ?Media;
    public static function generateThumbnail(string $path, int $width = 300, int $height = 300): ?string;
    public static function isValidImage(string $tmpPath, string $mimeType): bool;
}
```

### src/Admin/MediaController.php
- `index()`: lista galeria com paginação
- `upload()`: recebe $_FILES, valida, chama MediaManager::upload, retorna JSON
- `delete(int $id)`: remove arquivo + registro
- `json()`: retorna lista de imagens como JSON

### templates/admin/media.php
- Upload zone drag & drop
- Grid de imagens (4-6 colunas)
- Preview com thumbnail
- Checkbox seleção múltipla
- Botão delete em lote
- Paginação

### templates/admin/_media_modal.php
- Modal para seleção de imagem no block editor
- Grid de imagens
- Filtro/busca
- Botão "Selecionar"

### public/assets/media.js
- Drag & drop upload
- Preview antes de enviar
- Progresso simulado
- Seleção múltipla
- Delete com confirm

### public/assets/admin.css (modificado)
- Estilos para upload zone
- Grid de imagens
- Modal de mídia

## Validation
- MIME types: image/jpeg, image/png, image/gif, image/webp
- Max size: 5MB (5242880 bytes)
- Filename sanitize: remove acentos, espaços → underscore
- Unique filename: uniqid() prefix
