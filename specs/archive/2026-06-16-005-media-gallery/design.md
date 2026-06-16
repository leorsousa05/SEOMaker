# Design: Galeria de Mídia

## Architecture

### Upload Flow
1. Cliente arrasta ou seleciona imagens
2. JS valida tipo/tamanho
3. POST multipart para /admin/media/upload
4. PHP move arquivo para public/uploads/2026/06/uniqid_nome.ext
5. GD gera thumbnail: public/uploads/2026/06/thumb_uniqid_nome.ext
6. Salva metadados na tabela media
7. Retorna JSON com dados da imagem

### Storage Structure
```
public/uploads/
  2026/
    06/
      a1b2c3_imagem.jpg
      thumb_a1b2c3_imagem.jpg
```

### MediaController
- GET /admin/media — lista galeria
- POST /admin/media/upload — recebe upload
- GET /admin/media/delete/{id} — remove
- GET /admin/media/json — retorna JSON para modal

### Block Editor Integration
- Bloco image: abre modal de seleção
- Modal mostra grid de imagens da galeria
- Clique seleciona, botão "Usar esta imagem"
- URL da imagem vai para o campo src do bloco

## Thumbnail Sizes
- Thumb: 300x300px (crop center)
- Full: original

## Routes
- GET /admin/media
- POST /admin/media/upload
- GET /admin/media/delete/{id}
- GET /admin/media/json
