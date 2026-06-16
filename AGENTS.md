# AGENTS.md — SEO Template PHP

> Este arquivo é destinado a agentes de código. Ele descreve a arquitetura, convenções e comandos necessários para trabalhar neste projeto. O projeto utiliza **português (pt-BR)** em comentários, especificações, interface e mensagens de erro, então mantenha essa língua ao editar código, templates e documentação.

---

## 1. Visão geral do projeto

**Nome:** SEO Template PHP  
**Pacote Composer:** `seo/template`  
**Tipo:** Aplicação web monolítica em PHP puro com painel administrativo.

É um template completo para criação de sites otimizados para SEO. Inclui landing page pública, painel administrativo protegido por login, gerenciamento de páginas, upload de mídia, redirecionamentos, mensagens de contato e geração automática de recursos técnicos de SEO (meta tags, Open Graph, Twitter Cards, Schema.org JSON-LD, sitemap.xml e robots.txt).

---

## 2. Stack tecnológica e arquitetura

- **Backend:** PHP 8.1+ puro, sem framework full-stack.
- **Banco de dados:** SQLite em arquivo único (`config/database.sqlite`).
- **Autenticação:** Session-based (PHP nativo).
- **Email:** PHPMailer via Composer para SMTP; fallback para `mail()` nativo.
- **Frontend público:** HTML semântico, CSS vanilla (`public/assets/style.css`), JS vanilla (`public/assets/animations.js`).
- **Frontend admin:** CSS vanilla (`public/assets/admin.css`), JS vanilla (`tabs.js`, `block-editor.js`, `media.js`, `schema-editor.js`).
- **Templates:** PHP puro com sistema de layouts e partials (`templates/`).
- **Roteamento:** Router customizado em `App\Core\Router` (`public/index.php`).
- **Testes:** Framework de testes próprio baseado em `assert`/`assertTrue`/`assertEquals`; JS testado com `jsdom`.
- **Dependências PHP:** `phpmailer/phpmailer ^6.9`.
- **Dependências Node:** `playwright ^1.60.0` (produção/runtime checks) e `jsdom ^29.1.1` (dev, para testes JS).

### Arquitetura de runtime

1. Requisição chega em `public/index.php` (ou `public/router.php` no servidor embutido do PHP).
2. `src/autoload.php` registra autoloader PSR-4 para o namespace `App\` e carrega o autoloader do Composer.
3. `App\Core\Seeder::run()` garante que as tabelas, usuário admin padrão, configurações e páginas iniciais existam.
4. Redirecionamentos ativos são verificados antes do roteamento.
5. `App\Core\Router` faz o dispatch para controllers em `App\Admin\*` ou `App\Public\*`.
6. Controllers usam `App\Core\View` para renderizar templates em `templates/`.
7. Modelos em `App\Models\*` encapsulam acesso a dados via `App\Core\Database` (PDO SQLite).

---

## 3. Estrutura de diretórios

```
.
├── config/                 # Banco SQLite (database.sqlite)
├── public/                 # Document root
│   ├── index.php           # Entry point principal
│   ├── router.php          # Helper para php -S (serve arquivos estáticos)
│   ├── .htaccess           # Rewrite Apache para index.php
│   ├── uploads/            # Arquivos enviados pelo admin
│   └── assets/             # CSS, JS, fontes
├── src/                    # Código PHP da aplicação
│   ├── autoload.php        # Autoloader PSR-4 customizado
│   ├── Admin/              # Controllers do painel administrativo
│   ├── Content/            # Gerenciamento de conteúdo (block editor, mídia)
│   ├── Core/               # Infraestrutura (Router, Database, Config, View, Mailer, Seeder)
│   ├── Models/             # Modelos de dados (Page, ContactMessage, Redirect, Media, Address, User)
│   ├── Public/             # Controllers do site público
│   └── Seo/                # Motor de SEO (meta tags, schemas, sitemap, robots)
├── templates/              # Templates PHP
│   ├── admin/              # Layouts e páginas do admin
│   └── public/             # Layouts e páginas do site
├── tests/                  # Testes
│   ├── php/                # Testes PHP customizados
│   ├── js/                 # Testes JS com jsdom
│   └── run.php             # Suite completa (PHP + JS)
├── specs/                  # Especificações de desenvolvimento
│   ├── archive/            # Specs concluídas
│   ├── changes/            # Mudanças pendentes/em andamento
│   ├── living/             # Especificações vigentes
│   └── templates/          # Templates de spec
├── composer.json           # Dependência: phpmailer/phpmailer
├── package.json            # Dependências Node: playwright, jsdom
└── vendor/                 # Dependências Composer
```

---

## 4. Configuração e execução local

### Pré-requisitos

- PHP 8.1+ com extensões: `pdo_sqlite`, `gd` (opcional, para thumbnails), `fileinfo`.
- Composer.
- Node.js 18+ (apenas para rodar testes JS).

### Instalação

```bash
composer install
npm install
```

> Nota: `node_modules` e `vendor` já podem estar presentes no ambiente. O banco SQLite em `config/database.sqlite` é criado automaticamente pelo `Seeder` na primeira requisição.

### Servidor de desenvolvimento

Use o servidor embutido do PHP apontando para `public/`:

```bash
php -S localhost:8080 -t public public/router.php
```

Ou com Apache/Nginx, configure o document root para `public/` e use o rewrite de `public/.htaccess`:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

### Acesso padrão

- Site público: `http://localhost:8080/`
- Painel admin: `http://localhost:8080/admin`
- Credenciais padrão: **admin / admin123**

> **Importante:** altere a senha padrão em produção.

---

## 5. Build e comandos de teste

Não há build de assets (CSS/JS são arquivos estáticos). Os testes são executados diretamente.

### Rodar todos os testes

```bash
php tests/run.php
```

Esse comando executa todos os testes PHP em `tests/php/` e, se o Node estiver disponível, também executa `tests/js/tabs.test.js`.

### Rodar testes PHP individualmente

```bash
php tests/php/PageTest.php
php tests/php/SeoManagerTest.php
php tests/php/BlockEditorTest.php
# etc.
```

### Rodar testes JS individualmente

```bash
node tests/js/tabs.test.js
```

Requer `jsdom` instalado (`npm install`).

### Verificar sintaxe PHP

```bash
find src tests -name '*.php' -exec php -l {} \;
```

---

## 6. Diretrizes de estilo e convenções

### PHP

- Todo arquivo PHP deve começar com `<?php` e `declare(strict_types=1);`.
- Namespaces seguem PSR-4: `App\{Admin|Content|Core|Models|Public|Seo}`.
- Classes usam PascalCase; métodos e propriedades, camelCase ou snake_case conforme o contexto dos modelos (propriedades refletem colunas do banco).
- Use type hints sempre que possível (`?int`, `string`, `array`, `mixed`).
- Documente arrays complexos com PHPDoc (`/** @var array<...> */`, `/** @return array<...> */`).
- Escaping de output em templates: use `htmlspecialchars($text, ENT_QUOTES, 'UTF-8')` ou `App\Core\View::escape()`.
- Acesso ao banco sempre via `App\Core\Database` (prepared statements obrigatórios).

### JavaScript

- Use IIFE e `'use strict';`.
- Sem framework; vanilla JS puro.
- Exponha APIs globais quando necessário (ex: `window.Tabs = { init: ... }`).

### CSS

- CSS vanilla em `public/assets/`.
- Design system baseado em:
  - Fontes: `Syne` (títulos/display) e `Inter` (corpo).
  - Paleta: verde esmeralda (`emerald`) como cor de destaque principal.
  - Admin com suporte a modo escuro via `data-theme="dark"` + `localStorage` + `prefers-color-scheme`.

### Banco de dados

- Tabelas e colunas usam nomes em inglês (`pages`, `settings`, `contact_messages`, `redirects`, `media`, `addresses`, `users`).
- Colunas de data: `created_at`, `updated_at` no formato `Y-m-d H:i:s`.
- Flags booleanas são armazenadas como `INTEGER` (0/1).
- JSON é armazenado em colunas `TEXT` (`schema_data`, `content_blocks`).

### Mensagens e UI

- Mantenha a interface, labels e mensagens de erro em **português (pt-BR)**, exceto termos técnicos que já estão em inglês (ex: "Dashboard", "Redirects", "Settings").

---

## 7. Instruções de teste

- O projeto não usa PHPUnit. Os testes são scripts PHP que definem funções locais como `assertTrue`, `assertEquals`, `assertContact`, etc.
- Cada teste carrega `src/autoload.php` e executa o `Seeder` quando necessário.
- O banco de testes é o mesmo `config/database.sqlite` usado em desenvolvimento. Se necessário, exclua o arquivo para reiniciar o estado.
- Testes devem ser idempotentes quando possível; evitem depender de dados que outros testes criaram sem preparar o estado dentro do próprio arquivo.
- Para adicionar um novo teste PHP, crie um arquivo em `tests/php/NomeTest.php` e inclua-o em `tests/run.php`.
- Para adicionar um novo teste JS, crie um arquivo em `tests/js/` e ajuste `tests/run.php` para executá-lo.

---

## 8. Considerações de segurança

- **Senha padrão:** o seeder cria o usuário `admin` com senha `admin123`. Altere imediatamente em qualquer ambiente acessível.
- **Uploads:** `MediaManager` valida mime-type e dimensões de imagem; aceita apenas JPG, PNG, GIF e WEBP; limite de 5MB. Uploads ficam em `public/uploads/`.
- **SQL Injection:** todas as queries passam por prepared statements em `App\Core\Database`.
- **XSS:** escape toda saída em templates com `htmlspecialchars` ou `View::escape()`. O `BlockEditor::sanitizeHtml()` remove `<script>`, `<iframe>`, atributos `on*` e `javascript:`.
- **CSRF:** o projeto **não implementa tokens CSRF** atualmente. Se adicionar ações sensíveis via POST, considere incluir proteção CSRF.
- **Rate limiting:** o formulário de contato possui rate limit de 1 minuto por sessão (`ContactMessage::isRateLimited`).
- **Credenciais SMTP:** senhas SMTP são armazenadas em texto simples na tabela `settings`. Em produção, prefira variáveis de ambiente ou arquivo fora do web root.

---

## 9. Processo de desenvolvimento com specs

O projeto utiliza um processo orientado a especificações em `specs/`:

- `specs/living/` — especificações vigentes que devem ser respeitadas.
- `specs/changes/` — propostas de mudança ativas (design.md, proposal.md, specs/, tasks.md, `.spec.yaml`).
- `specs/archive/` — especificações já concluídas.
- `specs/templates/` — templates para novas specs.

Antes de implementar uma funcionalidade grande, crie ou consulte a spec correspondente. O arquivo `.spec.yaml` contém metadados como `id`, `status`, `scope` e `type`.

---

## 10. Deploy

O deploy é manual e consiste em copiar os arquivos para um servidor web configurado com:

- Document root apontando para `public/`.
- Módulo `mod_rewrite` habilitado (Apache) ou regra equivalente (Nginx) para rotear tudo para `public/index.php`.
- Permissões de escrita em `config/` (SQLite) e `public/uploads/`.
- PHP 8.1+ com `pdo_sqlite`.

Não há pipeline de CI/CD, Docker ou scripts de deploy automatizados no repositório.

---

## Resumo rápido para agentes

- Use **PHP puro**, **SQLite** e **vanilla JS/CSS**.
- Mantenha comentários, labels e mensagens em **português (pt-BR)**.
- Adicione código em `src/`, templates em `templates/` e assets em `public/assets/`.
- Teste com `php tests/run.php`.
- Rode localmente com `php -S localhost:8080 -t public public/router.php`.
- Siga as especificações em `specs/living/` e `specs/changes/`.
- Não assuma frameworks; o projeto é intencionalmente minimalista.
