# Parte 4: Sitemap XML, Robots.txt e Formulário de Contato

## Contexto
O template já possui:
- Admin com painel de páginas, media, settings e schema
- Configurações de SMTP salvas no banco
- Frontend básico renderizando páginas salvas

Falta conectar o ciclo de SEO + leads:
1. Google/indexadores precisam descobrir as páginas → **Sitemap XML**
2. Robôs precisam saber o que indexar → **Robots.txt**
3. Visitantes precisam enviar mensagens → **Formulário de contato**
4. Mensagens precisam chegar ao cliente → **Envio de email via SMTP**

## Escopo

### 1. Sitemap XML (`GET /sitemap.xml`)
- Gera XML válido de sitemap.org
- Lista todas as páginas `is_active = 1`
- Inclui `<loc>`, `<lastmod>`, `<changefreq>`, `<priority>`
- Homepage tem prioridade 1.0, outras 0.8
- Content-Type `application/xml`
- Link automático no `<head>` das páginas para descoberta

### 2. Robots.txt (`GET /robots.txt`)
- Se config `robots_txt_custom` estiver preenchida, usa ela
- Senão, gera padrão apontando pro sitemap
- Exemplo padrão:
  ```
  User-agent: *
  Allow: /
  Sitemap: https://example.com/sitemap.xml
  ```

### 3. Formulário de Contato (`POST /contact`)
- Campos: name, email, phone (opcional), message
- Validação server-side
- Rate limiting simples por sessão IP (1 envio / 60s)
- Salva mensagens no banco (`contact_messages`)
- Envia email via SMTP usando PHPMailer
- Templates de email em texto simples (depois pode vir HTML)
- Feedback visual: toast/flash message

### 4. Admin Inbox (simples)
- Nova aba no admin: "Mensagens"
- Lista mensagens recebidas com status (novo, respondido, arquivado)
- Ações: marcar como respondido/arquivado, excluir
- Notificação no sidebar quando há mensagens não lidas

## Fora do escopo
- Email em HTML elaborado
- Responder email direto pelo admin
- Webhook / notificações push
- Captcha (por simplicidade)

## Testes
- PHP Unit tests para SitemapBuilder, RobotsBuilder, ContactController
- Testes de validação de formulário
- Testes de geração de XML
