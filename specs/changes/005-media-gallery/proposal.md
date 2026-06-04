# Proposal: Galeria de Mídia

## WHY
O block editor já suporta imagens e galerias, mas não há como fazer upload de imagens. O cliente precisa de uma galeria visual para gerenciar imagens do site.

## Scope
- Upload de imagens (drag & drop + input file)
- Armazenamento em public/uploads/YYYY/MM/
- Thumbnails automáticos via GD
- Grid visual no admin com preview
- Seleção de imagens no block editor
- Delete individual ou em lote

## Constraints
- PHP GD extension para thumbnails
- Tipos: jpg, jpeg, png, gif, webp
- Max 5MB por arquivo
- Sem frameworks frontend
