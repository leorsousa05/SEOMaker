# Design: Auto FAQPage Schema from FAQ Block

## Overview
Adicionar um método em `SeoManager` que inspeciona `Page::$contentBlocks` (array JSON), encontra blocos do tipo `faq` e constrói um schema `FAQPage` válido. O schema é então serializado em JSON-LD e injetado no layout público.

## Proposed Directory & File Structure
```
/home/arch/codes/template-seo/
├── src/
│   ├── Seo/
│   │   └── SeoManager.php          (Modified)
│   └── Content/
│       └── BlockEditor.php         (No change)
├── templates/
│   └── public/
│       └── layout.php              (Modified)
├── tests/
│   ├── php/
│   │   └── FaqSchemaTest.php       (New)
│   └── run.php                     (Modified)
└── specs/changes/010-faq-schema-auto/
    └── ...
```

## Code Architecture & Design Patterns
- **Parser Pattern:** `SeoManager::extractFaqItems()` isola a extração.
- **Null Object Pattern:** retorna `null` quando não há FAQs, evitando renderização vazia.

## Data Model
```php
// Estrutura esperada de FAQPage
[
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'Pergunta?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Resposta em texto plano.',
            ],
        ],
    ],
]
```

## API Contracts
```php
// App\Seo\SeoManager
public static function faqSchema(Page $page): ?array;
private static function extractFaqItems(array $blocks): array;
public static function schemaJsonLd(Page $page): string; // extended to include FAQPage
```

## Flow Diagrams
### Render Flow
1. `layout.php` chama `SeoManager::schemaJsonLd($page)`.
2. `schemaJsonLd()` chama `SchemaGenerator::generate($page)` e `SeoManager::faqSchema($page)`.
3. Se `faqSchema` retornar array, é mesclado ou renderizado como script separado.
4. JSON-LD válido é impresso no HTML.

## State Management
- Somente leitura de `pages.content_blocks`.

## Error Handling
- JSON inválido: tratar como array vazio.
- Itens com pergunta/resposta vazia: ignorados.

## Performance Considerations
- Parse JSON a cada requisição; aceitável para template. Pode ser otimizado futuramente com cache.

## Security Considerations
- Sanitizar texto da resposta (strip tags) antes de inserir no JSON-LD.
- Escapar JSON com `json_encode(..., JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG)`.
