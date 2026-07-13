# Proposal: Galeria de Mídia (Media Gallery)

## 📌 Contexto & Motivação (WHY)
Atualmente, o CMS SEOMaker possui um editor de blocos funcional que suporta a inserção de blocos do tipo imagem e galeria. No entanto, não há um mecanismo integrado para que o usuário possa fazer o upload de novas imagens diretamente pela interface administrativa, nem gerenciar os arquivos de mídia armazenados. O usuário é forçado a enviar arquivos manualmente por FTP ou depender de caminhos estáticos externos.

Esta proposta visa implementar uma **Galeria de Mídia centralizada**, permitindo o upload seguro, visualização, paginação, exclusão (individual e em lote) e integração fluida com o editor de blocos, elevando a usabilidade da plataforma e garantindo uma experiência autossuficiente de edição de conteúdo.

---

## 🎯 Escopo Funcional (WHAT)

### 1. Upload de Arquivos
- Suporte a upload via drag & drop e por seleção clássica de arquivos (input do tipo file).
- Validação no lado do cliente (tamanho e tipo) e validação robusta no servidor.
- Salvamento dos arquivos originais organizados de forma cronológica no diretório de destino: `/public/uploads/YYYY/MM/`.
- Sanitização de nomes de arquivos para evitar caracteres especiais, espaços ou conflitos de codificação.
- Geração de nomes únicos para evitar colisões (utilizando prefixo único `uniqid()`).

### 2. Geração Automática de Thumbnails
- Uso da extensão **GD** do PHP para criar automaticamente uma versão miniatura (thumbnail) de cada imagem enviada.
- Dimensão fixa de **300x300px** com corte centralizado (crop center) para manter um grid simétrico na interface.
- Armazenamento da miniatura no mesmo diretório do arquivo original, utilizando o prefixo `thumb_`.

### 3. Interface Administrativa (Grid Visual)
- Exibição de um grid responsivo das imagens cadastradas, ordenadas de forma decrescente (da mais recente para a mais antiga).
- Paginação robusta (padrão de 24 itens por página).
- Detalhes visuais de cada mídia (nome original, tamanho em KB, dimensões em pixels e formato).
- Opção para remoção individual com confirmação prévia.
- Seleção múltipla para remoção em lote (batch delete), otimizando o fluxo de limpeza do servidor.

### 4. Integração com o Editor de Blocos (Block Editor)
- Criação de uma janela modal de seleção de mídia reutilizável.
- Ao clicar para adicionar ou editar uma imagem/galeria no editor de blocos, abre-se a modal que carrega dinamicamente via AJAX a lista de mídias cadastradas.
- Possibilidade de selecionar uma imagem e inseri-la diretamente como a URL de origem (`src`) do bloco correspondente.

### 5. Exclusão Física e Lógica
- A remoção de uma mídia deve deletar tanto o registro do banco de dados (tabela `media`) quanto os arquivos físicos no servidor (imagem original e thumbnail).

---

## 🚫 Fora de Escopo (Out of Scope)
- Edição de imagens na interface administrativa (crop, rotação, filtros).
- Suporte a formatos de arquivo não baseados em imagem (PDFs, DOCX, arquivos zip).
- Integração com serviços de armazenamento em nuvem (AWS S3, Google Cloud Storage, Cloudinary). Todos os arquivos serão armazenados localmente.
- Otimização avançada de imagens (conversão automática para formatos modernos como WebP se o arquivo original for PNG/JPG, compactação lossless).

---

## ⚙️ Restrições & Requisitos Não-Funcionais (Constraints)
- **Tecnologias**: Sem frameworks CSS/JS externos no painel de upload (utilizar Vanilla JavaScript e CSS nativo já presentes no sistema).
- **Formatos Permitidos**: Apenas `jpg`, `jpeg`, `png`, `gif` e `webp`.
- **Limites de Tamanho**: Limite máximo de **5MB** por arquivo.
- **Dependência Técnica**: Extensão `gd` do PHP ativada no servidor para manipulação de thumbnails. Fallback sem erro se a extensão não estiver ativa (apenas desativa thumbnails de forma elegante).
- **Persistência**: SQLite como banco de dados relacional por meio do adaptador `App\Core\Database`.

---

## 📋 Requisitos de Rastreabilidade

| ID | Requisito | Status / Onde está definido |
| :--- | :--- | :--- |
| **REQ-01** | Upload de arquivos via Drag & Drop | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (Frontend UI) |
| **REQ-02** | Validação de extensão (JPG, PNG, GIF, WEBP) | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (Validation Rule) |
| **REQ-03** | Limite máximo de 5MB por arquivo | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (Validation Rule) |
| **REQ-04** | Geração de Thumbnail 300x300px via PHP GD | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (MediaManager Contract) |
| **REQ-05** | Grid de fotos com paginação (24 itens) | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (MediaController Contract) |
| **REQ-06** | Deleção de arquivos físicos ao excluir registro | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (MediaManager Contract) |
| **REQ-07** | Integração da modal com o Block Editor | [design.md](file:///home/arch/codes/SEOMaker/specs/changes/005-media-gallery/design.md) (Block Editor Interaction) |
