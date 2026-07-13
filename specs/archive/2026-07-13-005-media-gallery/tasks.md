# Tasks: 005-media-gallery

Este checklist detalha as etapas de verificação e implementação restantes para homologação da Galeria de Mídia.

## 📁 1. Verificação da Estrutura e Rotas
- [x] Confirmar existência de `src/Models/Media.php`
- [x] Confirmar existência de `src/Content/MediaManager.php`
- [x] Confirmar existência de `src/Admin/MediaController.php`
- [x] Confirmar mapeamento das rotas de mídia em `public/index.php`
  - [x] `GET /admin/media` -> `MediaController@index`
  - [x] `POST /admin/media/upload` -> `MediaController@upload`
  - [x] `GET /admin/media/delete/{id}` -> `MediaController@delete`
  - [x] `GET /admin/media/json` -> `MediaController@json`

## 💻 2. Verificação das Views e Assets Frontend
- [x] Verificar interface principal `templates/admin/media.php` (layout do grid e drag & drop)
- [x] Verificar modal de seleção `templates/admin/_media_modal.php`
- [x] Verificar script client-side `public/assets/media.js`
- [x] Verificar integração com bloco de imagem em `public/assets/block-editor.js`
- [x] Verificar estilos em `public/assets/admin.css`

## 🗄️ 3. Banco de Dados e Migração
- [x] Confirmar criação automática da tabela `media` em `src/Core/Seeder.php`
- [x] Validar persistência inserindo uma mídia e verificando o ID incremental gerado

## 🧪 4. Suíte de Testes (PHPUnit)
- [x] Criar arquivo de testes `tests/php/MediaManagerTest.php`
- [x] Implementar teste: Validação de extensões permitidas (`jpg`, `png`, `gif`, `webp`)
- [x] Implementar teste: Rejeição de extensões proibidas/ameaças (`php`, `pdf`, `html`)
- [x] Implementar teste: Validação de limite de tamanho de arquivo (sucesso <= 5MB, erro > 5MB)
- [x] Implementar teste: Geração correta do thumbnail 300x300px com prefixo `thumb_` via GD
- [x] Implementar teste: Remoção lógica (registro no banco) e física (arquivos físicos e thumbs deletados)
- [x] Implementar teste: Sanitização de nomes de arquivos com caracteres especiais e espaços

## 🏁 5. Homologação e Finalização
- [x] Rodar suíte completa de testes locais e garantir status `PASS`
- [x] Marcar status do `.spec.yaml` como completo
- [x] Commit das alterações da spec e testes seguindo os padrões convencionais
