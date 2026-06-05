# Tarefas — Parte 4

## Sitemap
- [ ] Criar `src/Seo/SitemapBuilder.php`
- [ ] Rota `GET /sitemap.xml` no router
- [ ] Adicionar `<link rel="sitemap">` no `<head>` do frontend
- [ ] Testes: XML válido, URLs corretas, homepage priority 1.0

## Robots
- [ ] Criar `src/Seo/RobotsBuilder.php`
- [ ] Rota `GET /robots.txt` no router
- [ ] Testes: custom config, default generation

## Banco
- [ ] Migration para tabela `contact_messages`
- [ ] Model `App\Models\ContactMessage`

## Email
- [ ] Instalar PHPMailer via Composer
- [ ] Criar `src/Content/ContactManager.php`
- [ ] Implementar envio SMTP com fallback
- [ ] Template de email

## Formulário
- [ ] Criar `src/Site/ContactController.php`
- [ ] Rota `POST /contact`
- [ ] Rate limit por sessão/IP
- [ ] Validação server-side
- [ ] Feedback flash/toast

## Admin Inbox
- [ ] `ContactMessagesController` no admin
- [ ] Template `templates/admin/messages.php`
- [ ] Badge no sidebar quando há mensagens novas
- [ ] Ações: mark replied, archive, delete

## Testes
- [ ] Testes PHP
- [ ] Rodar test suite completa
- [ ] Commit
