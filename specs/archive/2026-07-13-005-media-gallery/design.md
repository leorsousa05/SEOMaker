# Design: Galeria de Mídia (Media Gallery)

Este documento estabelece as especificações de design visual, comportamento de interface (UI/UX) e a arquitetura técnica da Galeria de Mídia do SEOMaker.

---

## 📂 Estrutura de Diretórios e Arquivos Afetados

Abaixo está o mapeamento dos arquivos envolvidos no escopo da funcionalidade:

```
SEOMaker/
├── config/
│   └── database.sqlite (persistência em tabela sqlite `media`)
├── public/
│   ├── assets/
│   │   ├── admin.css (estilos para a galeria, upload zone e modal)
│   │   ├── block-editor.js (integração do seletor com blocos image/gallery)
│   │   └── media.js (upload assíncrono, seleção múltipla e deleção via AJAX)
│   ├── uploads/ (repositório físico dos arquivos e thumbs)
│   │   └── YYYY/
│   │       └── MM/
│   └── index.php (registro das rotas HTTP da galeria)
├── src/
│   ├── Admin/
│   │   └── MediaController.php (processamento HTTP, auth e responses JSON/HTML)
│   ├── Content/
│   │   └── MediaManager.php (negócio, validação de arquivos, GD thumbs e I/O)
│   └── Models/
│       └── Media.php (DTO e métodos utilitários de formatação de mídia)
├── templates/
│   └── admin/
│       ├── _media_modal.php (modal AJAX reutilizável)
│       └── media.php (página gerencial da galeria no painel)
└── tests/
    └── php/
        └── MediaManagerTest.php (testes unitários e de integração de upload/I/O)
```

---

## 🎨 Direcionamento Estético e Narrativa de Marca

### 1. Narrativa do Caso de Uso (Case-Study Frame)
- **Problema:** Usuários administrativos precisam enviar, selecionar e apagar imagens sem sair do painel do CMS e sem depender de ferramentas de FTP de terceiros, garantindo integridade visual instantânea.
- **Público-alvo:** Produtores de conteúdo, consultores de SEO e administradores de sites que buscam agilidade operacional e foco na organização.
- **Insight:** Um gerenciador de arquivos de mídia deve parecer uma ferramenta de precisão, onde as mídias são organizadas e rotuladas como componentes de um inventário técnico, e não apenas uma coleção de fotos soltas.
- **Solução:** Criar uma galeria utilitária com estética industrial refinada, permitindo uploads instantâneos e seleção ágil diretamente no contexto de escrita.

### 2. Conceito Visual (Industrial/Utilitarian)
Optamos por uma direção estética **Industrial e Utilitária**. O design faz uso de tipografia geométrica de alta legibilidade, bordas finas e precisas de 1px separando cartões, contraste acentuado com uma cor de foco quente (Industrial Amber) que destaca estados ativos sem poluir visualmente o painel de SEO.

---

## 🎨 Sistema de Cores (Semantic Tokens)

O sistema utiliza cores HSL personalizadas derivadas para esta marca de utilidade:

| Token | Light Mode | Dark Mode | Aplicação |
| :--- | :--- | :--- | :--- |
| `--bg-primary` | `hsl(220, 15%, 97%)` | `hsl(220, 20%, 8%)` | Fundo principal da página |
| `--bg-surface` | `hsl(0, 0%, 100%)` | `hsl(220, 16%, 12%)` | Painéis e cartões |
| `--bg-elevated` | `hsl(0, 0%, 100%)` | `hsl(220, 16%, 16%)` | Modais e popovers |
| `--text-primary` | `hsl(220, 25%, 12%)` | `hsl(220, 15%, 92%)` | Títulos e texto principal |
| `--text-secondary`| `hsl(220, 12%, 40%)` | `hsl(220, 10%, 70%)` | Subtítulos e metadados |
| `--text-muted` | `hsl(220, 10%, 65%)` | `hsl(220, 8%, 45%)` | Placeholders e textos desativados |
| `--accent` | `hsl(25, 95%, 50%)` | `hsl(25, 95%, 55%)` | Ações principais e foco ativo (Amber) |
| `--success` | `hsl(142, 70%, 35%)` | `hsl(142, 68%, 45%)` | Upload completo e estados válidos |
| `--warning` | `hsl(38, 92%, 48%)` | `hsl(38, 92%, 55%)` | Alertas e limites aproximados |
| `--error` | `hsl(0, 84%, 48%)` | `hsl(0, 84%, 55%)` | Erro de upload ou exclusão |
| `--border` | `hsl(220, 15%, 88%)` | `hsl(220, 12%, 18%)` | Linhas delimitadoras e bordas de 1px |
| `--focus` | `hsla(25, 95%, 50%, 0.4)`| `hsla(25, 95%, 55%, 0.4)`| Outline de navegação por teclado |
| `--overlay` | `hsla(220, 25%, 10%, 0.5)`| `hsla(220, 25%, 5%, 0.8)` | Fundo da janela modal |

---

## 🔤 Sistema Tipográfico

| Nível | Fonte | Tamanho | Altura da Linha (LH) | Espaçamento (LS) | Peso | Uso |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **H1** | Sora | 32px / 2.0rem | 1.2 | -0.02em | 700 (Bold) | Cabeçalho da página |
| **H2** | Sora | 24px / 1.5rem | 1.3 | -0.01em | 600 (Semi-bold) | Título de seções / modais |
| **H3** | Sora | 16px / 1.0rem | 1.4 | 0 | 600 (Semi-bold) | Nome do arquivo no cartão |
| **Body** | Inter | 14px / 0.875rem | 1.5 | 0 | 400 (Regular) | Descrições e parágrafos |
| **Body-sm**| Inter | 12px / 0.75rem | 1.4 | +0.01em | 400 (Regular) | Metadados (bytes, pixels) |
| **Label** | Inter | 12px / 0.75rem | 1.2 | +0.02em | 500 (Medium) | Tags de formato, botões mini |
| **Button** | Sora | 14px / 0.875rem | 1.2 | 0 | 600 (Semi-bold) | CTAs principais |

---

## 📐 Design Tokens

### Escala de Espaçamento (Base 4px)
- `--space-xs`: `4px  / 0.25rem` (espaçamentos internos mínimos, badges)
- `--space-sm`: `8px  / 0.5rem`  (lacuna entre textos, labels e inputs)
- `--space-md`: `16px / 1rem`    (padding padrão de cartões e inputs)
- `--space-lg`: `24px / 1.5rem`  (espaçamento entre blocos do grid)
- `--space-xl`: `32px / 2rem`    (padding interno de modais e cabeçalhos)

### Escala de Raio de Borda (Corner Radius)
- `--radius-none`: `0`           (não utilizado neste estilo, preferência por suavidade sutil)
- `--radius-sm`:   `4px`         (tags, badges e miniaturas internas)
- `--radius-md`:   `6px`         (botões, campos de texto e inputs de arquivo)
- `--radius-lg`:   `8px`         (cartões da galeria e contêineres do grid)
- `--radius-xl`:   `12px`        (estrutura da janela modal)

### Escala de Sombras (Elevation)
- `--shadow-flat`: `none`
- `--shadow-sm`:   `0 1px 2px rgba(0, 0, 0, 0.05)` (estado padrão do cartão)
- `--shadow-md`:   `0 4px 12px rgba(0, 0, 0, 0.08)` (estado de hover no cartão)
- `--shadow-lg`:   `0 12px 32px rgba(0, 0, 0, 0.15)` (sombras projetadas de modais e dropdowns)

---

## 🧩 Especificações de Componentes

### 1. Zona de Drag & Drop (Upload Area)
- **Visual:** Contorno pontilhado utilizando a cor `--border`. Fundo ligeiramente destacado `--bg-surface`. Centralizado com ícone discreto e instrução textual clara.
- **Estado Dragover:** O contorno pontilhado muda para `--accent` e o fundo ganha uma transparência da cor de foco. Transição suave.
- **Erro de validação:** Borda pontilhada muda para `--error` temporariamente com uma animação leve de balanço lateral.

### 2. Cartão de Imagem (Grid Item)
- **Visual:** Borda sólida de 1px `--border`. Fundo `--bg-surface`.
- **Hover:** Borda muda para `--accent` com transição de 150ms. Leve elevação `--shadow-md`. Revela botão de exclusão rápida no canto superior direito.
- **Foco por Teclado:** Borda `--accent`, adiciona outline `--focus` de 2px.

### 3. Janela Modal (`_media_modal.php`)
- **Fundo Escurecido:** `--overlay` com filtro de desfoque (`backdrop-filter: blur(4px)`).
- **Contêiner:** Posicionado no centro da tela. Fundo `--bg-elevated`, bordas arredondadas `--radius-xl` e sombra `--shadow-lg`.

---

## 🎨 Layouts e Mockups (ASCII Wireframes)

### 1. Tela Geral da Galeria (`/admin/media`)
```
+-----------------------------------------------------------------------------------+
|  [SEO Maker Admin]  Páginas  | Mídia | Mensagens | Configurações      [Logout]   |
+-----------------------------------------------------------------------------------+
|  Galeria de Mídia                                                                 |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  | Drag & Drop suas fotos aqui ou clique para selecionar (Máx 5MB)             |  |
|  +-----------------------------------------------------------------------------+  |
|                                                                                   |
|  [Ações em lote: Deletar Selecionados]                                            |
|                                                                                   |
|  +---------------+  +---------------+  +---------------+  +---------------+       |
|  | [x]           |  | [x]           |  | [ ]           |  | [ ]           |       |
|  |               |  |               |  |               |  |               |       |
|  |   [Image      |  |   [Image      |  |   [Image      |  |   [Image      |       |
|  |    Preview]   |  |    Preview]   |  |    Preview]   |  |    Preview]   |       |
|  |               |  |               |  |               |  |               |       |
|  | gato.jpg      |  | cão.jpg       |  | logo.png      |  | banner.webp   |       |
|  | 100KB - 300x30|  | 250KB - 300x30|  | 50KB - 300x300|  | 400KB - 300x30|       |
|  |               |  |               |  |               |  |               |       |
|  | [Visualizar][x|  | [Visualizar][x|  | [Visualizar][x|  | [Visualizar][x|       |
|  +---------------+  +---------------+  +---------------+  +---------------+       |
|                                                                                   |
|  Páginas: [« Ant] [1] [2] [3] [Prox »]                                            |
+-----------------------------------------------------------------------------------+
```

### 2. Modal do Seletor no Block Editor
```
+-----------------------------------------------------------------------------------+
|  Selecione uma Imagem da Galeria                                              [X] |
+-----------------------------------------------------------------------------------+
|  [Pesquisar imagem...]                                                            |
|                                                                                   |
|  +-------------+  +-------------+  +-------------+  +-------------+               |
|  |             |  |             |  |             |  |             |               |
|  |   [Image    |  |   [Image    |  |   [Image    |  |   [Image    |               |
|  |    Preview] |  |    Preview] |  |    Preview] |  |    Preview] |               |
|  |             |  |             |  |             |  |             |               |
|  |  gato.jpg   |  |  cão.jpg    |  |  logo.png   |  | banner.webp |               |
|  +-------------+  +-------------+  +-------------+  +-------------+               |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  | Imagem Selecionada: /uploads/2026/07/666a1b2c3_meu-gato.jpg                 |  |
|  +-----------------------------------------------------------------------------+  |
|                                                                                   |
|                                                     [Cancelar] [Usar Imagem]      |
+-----------------------------------------------------------------------------------+
```

---

## 🔄 Fluxos Alternativos e Estados Reais (Real-State Specs)

1. **Estado de Carregamento (Loading):**
   - Durante o upload, a zona pontilhada é substituída por uma barra de progresso linear fina com cor `--accent` indicando a porcentagem concluída.
   - O grid exibe cartões em formato de esqueleto (shimmer) com fundo `--bg-surface` pulsando suavemente.
2. **Estado Vazio (Empty State):**
   - Caso não existam mídias cadastradas, a galeria exibe um painel centralizado com a mensagem "Nenhuma mídia encontrada" em `--text-secondary`, acompanhada de uma pequena ilustração SVG minimalista de uma câmera e um link/botão rápido para iniciar o upload.
3. **Estado de Erro (Error State):**
   - Em caso de falha de validação ou erro de conexão, uma caixa de toast vermelha com fundo baseado em `--error` surge no canto inferior direito com um botão de fechar automático após 5 segundos.
4. **Estado Sem Conexão (Offline):**
   - Caso o navegador perca a conexão à internet, os botões de upload e exclusão tornam-se inativos (`disabled`) e um banner discreto `--warning` indica "Você está offline. Operações de mídia indisponíveis".

---

## 🎬 Coreografia de Animação

| Animação | Gatilho | Propriedades | Duração | Easing (Curva) | Fallback (Motion Reduzido) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Grid Reveal** | Load da página | opacity, translateY | 300ms | `cubic-bezier(0.16, 1, 0.3, 1)` | Opacidade instantânea sem movimentação |
| **Card Hover Lift** | Hover no Cartão | transform, shadow | 150ms | `ease-out` | Sem elevação, apenas alteração de borda |
| **Modal Scale Enter**| Clique de Abrir | opacity, transform | 200ms | `cubic-bezier(0.34, 1.56, 0.64, 1)` | Transição linear simples de opacidade |
| **Upload Progress** | upload (progresso) | width | 100ms | `linear` | Sem animação suave |

---

## 🔌 Especificações Técnicas e Arquitetura do Backend

### 1. Padrão MVC e Isolamento de Responsabilidades
A arquitetura baseia-se na separação estrita da apresentação e manipulação de arquivos:
- `App\Models\Media` atua como DTO de dados armazenados e utilitário de formatação.
- `App\Content\MediaManager` controla a lógica de upload físico, GD thumbnails, deleção e validações estritas de tamanho e tipo MIME.
- `App\Admin\MediaController` manipula requisições HTTP do painel de administração e expõe saídas HTML e JSON.

### 2. Esquema do Banco de Dados SQLite `media`
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

### 3. Contratos de Código (Signatures)

#### App\Models\Media
```php
namespace App\Models;

class Media
{
    public ?int $id = null;
    public string $filename = '';
    public string $original_name = '';
    public string $mime_type = '';
    public int $size_bytes = 0;
    public ?int $width = null;
    public ?int $height = null;
    public string $path = '';
    public ?string $created_at = null;

    public static function fromArray(array $data): self;
    public function thumbUrl(): string;
    public function humanSize(): string;
}
```

#### App\Content\MediaManager
```php
namespace App\Content;

use App\Models\Media;

class MediaManager
{
    public static function upload(array $file): array;
    public static function delete(int $id): bool;
    public static function list(int $page = 1, int $perPage = 24): array;
    public static function count(): int;
    public static function find(int $id): ?Media;
    public static function generateThumbnail(string $sourcePath, string $destDir, string $filename): ?array;
    public static function isValidImage(string $tmpPath, string $mimeType): bool;
}
```

#### App\Admin\MediaController
```php
namespace App\Admin;

class MediaController
{
    public function index(): void;
    public function upload(): void;
    public function delete(array $params): void;
    public function json(): void;
}
```

### 4. API JSON Contracts

#### Resposta de Upload (`POST /admin/media/upload`)
```json
{
  "success": true,
  "files": [
    {
      "success": true,
      "id": 1,
      "filename": "66a1b2c3_nome-do-arquivo.jpg",
      "path": "/uploads/2026/07/66a1b2c3_nome-do-arquivo.jpg",
      "thumb": "/uploads/2026/07/thumb_66a1b2c3_nome-do-arquivo.jpg",
      "width": 1920,
      "height": 1080
    }
  ]
}
```

#### Resposta de Listagem AJAX (`GET /admin/media/json?page=1&perPage=24`)
```json
{
  "success": true,
  "items": [
    {
      "id": 1,
      "filename": "66a1b2c3_nome-do-arquivo.jpg",
      "original_name": "nome-do-arquivo.jpg",
      "mime_type": "image/jpeg",
      "size_bytes": 204800,
      "width": 1920,
      "height": 1080,
      "path": "/uploads/2026/07/66a1b2c3_nome-do-arquivo.jpg",
      "created_at": "2026-07-13 11:46:00"
    }
  ],
  "page": 1,
  "perPage": 24
}
```

---

## 🧪 Plano de Testes (PHPUnit)

Toda a lógica contida em `MediaManager.php` deve ser coberta por testes integrados no arquivo `tests/php/MediaManagerTest.php`:
1. **Validação de Tipos Permitidos:** Testar se extensões JPG, PNG, GIF, WEBP são aceitas e se outros tipos (PDF, EXE, PHP) são rejeitados via verificação real de MIME type.
2. **Validação de Limites de Tamanho:** Verificar que arquivos acima de 5MB geram erro controlado e abaixo passam com sucesso.
3. **Geração de Miniatura:** Se a extensão GD estiver ativa, verificar se a miniatura 300x300px é gerada corretamente no diretório físico com o prefixo `thumb_`.
4. **Deleção Consistente:** Confirmar que ao apagar uma mídia, tanto o registro do banco de dados quanto os dois arquivos no disco (original + miniatura) são apagados.
5. **Sanitização de Nome:** Testar se nomes com acentuações e caracteres especiais são sanitizados para caixa baixa e underscores de forma correta.

---

## 📋 Lista de Verificação Pré-Implementação (Checklist)

- [x] Narrativa de marca conecta a direção visual com a proposta do produto.
- [x] Conceito visual é único e personalizado para a experiência de SEO e CMS.
- [x] O sistema de cores utiliza variáveis HSL customizadas, com suporte a temas claro e escuro.
- [x] O sistema de tipografia define famílias, tamanhos e pesos customizados.
- [x] Os tokens de design definem escalas de espaçamento, cantos arredondados e sombras.
- [x] Razões de contraste mínimas (4.5:1 para texto normal, 3:1 para gráficos) são garantidas.
- [x] Alvos de toque físico e elementos de botão possuem altura/largura de pelo menos 44px.
- [x] Lógicas de feedback visual para Loading, Empty, Error, Success, Offline e Skeletons estão detalhadas.
- [x] Animações e transições possuem fallbacks para usuários com preferência por movimentos reduzidos.
- [x] Os contratos de classes, banco de dados e APIs do backend estão consistentes com a especificação original.
