# Living Spec: Galeria de Mídia (Media Gallery)

Este documento descreve o estado atual da Galeria de Mídia do SEOMaker, servindo como a fonte de verdade para a funcionalidade implementada.

---

## 🎯 Objetivo
Permitir que usuários façam o upload, visualização, listagem paginada e exclusão (individual e em lote) de arquivos de imagem no painel administrativo, com suporte para geração automática de miniaturas e seleção direta de mídias a partir do editor de blocos.

---

## 📂 Estrutura de Arquivos
- **Model:** `src/Models/Media.php`
- **Gerenciador de Negócios:** `src/Content/MediaManager.php`
- **Controller:** `src/Admin/MediaController.php`
- **Páginas de Apresentação:** `templates/admin/media.php` e `templates/admin/_media_modal.php`
- **Assets de Client-side:** `public/assets/media.js` e modificações em `admin.css`, `block-editor.js`
- **Testes:** `tests/php/MediaManagerTest.php`

---

## 🗄️ Persistência de Dados (SQLite)

Tabela `media` de persistência dos metadados de arquivos:

```sql
CREATE TABLE IF NOT EXISTS media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    filename TEXT NOT NULL,
    original_name TEXT,
    mime_type TEXT,
    size_bytes INTEGER DEFAULT 0,
    width INTEGER,
    height INTEGER,
    path TEXT NOT NULL,
    created_at TEXT
);
```

---

## 🔌 Contratos de Código

### 1. DTO `App\Models\Media`
Representa um registro imutável no sistema.
- `id` (?int)
- `filename` (string)
- `original_name` (string)
- `mime_type` (string)
- `size_bytes` (int)
- `width` (?int)
- `height` (?int)
- `path` (string)
- `created_at` (?string)
- `fromArray(array $data): self`
- `thumbUrl(): string` (retorna o path relativo da miniatura `thumb_`)
- `humanSize(): string` (retorna tamanho formatado, ex: "120.5 KB" ou "2.1 MB")

### 2. `App\Content\MediaManager`
Centraliza operações de escrita, validações e manipulação física via GD:
- `upload(array $file): array` (valida MIME, tamanho < 5MB, salva original, gera miniatura e grava no SQLite)
- `delete(int $id): bool` (exclui registro do banco e apaga arquivos físicos de imagem e miniatura)
- `list(int $page, int $perPage): array` (retorna registros de mídia ordenados decrescentemente por ID)
- `count(): int` (totalizador de registros)
- `find(int $id): ?Media` (retorna instância de DTO Media)
- `generateThumbnail(string $sourcePath, string $destDir, string $filename): ?array` (corta imagem ao centro em proporção 300x300px usando a extensão GD)
- `isValidImage(string $tmpPath, string $mimeType): bool` (segurança de upload validando tipo e estrutura de cabeçalho)

### 3. `App\Admin\MediaController`
Processamento de requisições web administrativas:
- `index(): void` (lista galeria administrativa)
- `upload(): void` (manipula uploads múltiplos ou individuais retornando JSON)
- `delete(array $params): void` (remove mídia baseado no ID de rota e redireciona com flash message)
- `json(): void` (listagem assíncrona paginada para mídias na janela modal)

---

## 🔌 API JSON Endpoints

### 1. Upload (`POST /admin/media/upload`)
**Response:**
```json
{
  "success": true,
  "files": [
    {
      "success": true,
      "id": 1,
      "filename": "66a1b2c3_meu-gato.jpg",
      "path": "/uploads/2026/07/66a1b2c3_meu-gato.jpg",
      "thumb": "/uploads/2026/07/thumb_66a1b2c3_meu-gato.jpg",
      "width": 1200,
      "height": 900
    }
  ]
}
```

### 2. Listagem AJAX (`GET /admin/media/json`)
**Response:**
```json
{
  "success": true,
  "items": [
    {
      "id": 1,
      "filename": "66a1b2c3_meu-gato.jpg",
      "original_name": "meu-gato.jpg",
      "mime_type": "image/jpeg",
      "size_bytes": 102400,
      "width": 1200,
      "height": 900,
      "path": "/uploads/2026/07/66a1b2c3_meu-gato.jpg",
      "created_at": "2026-07-13 11:46:00"
    }
  ],
  "page": 1,
  "perPage": 24
}
```
