# Tarefas — Parte 4

## Sitemap
- [x] Criar `src/Seo/SitemapBuilder.php`
- [x] Rota `GET /sitemap.xml` no router
- [x] Adicionar `<link rel="sitemap">` no `<head>` do frontend
- [x] Testes: XML válido, URLs corretas, homepage priority 1.0

## Robots
- [x] Criar `src/Seo/RobotsBuilder.php`
- [x] Rota `GET /robots.txt` no router
- [x] Testes: custom config, default generation

## Banco
- [x] Migration para tabela `contact_messages`
- [x] Model `App\Models\ContactMessage`

## Email
- [x] Instalar PHPMailer via Composer
- [x] Criar `src/Content/ContactManager.php`
- [x] Implementar envio SMTP com fallback
- [x] Template de email

## Formulário
- [x] Criar `src/Site/ContactController.php`
- [x] Rota `POST /contact`
- [x] Rate limit por sessão/IP
- [x] Validação server-side
- [x] Feedback flash/toast

## Admin Inbox
- [x] `ContactMessagesController` no admin
- [x] Template `templates/admin/messages.php`
- [x] Badge no sidebar quando há mensagens novas
- [x] Ações: mark replied, archive, delete

## Testes
- [x] Testes PHP
- [x] Rodar test suite completa
- [ ] Commit
