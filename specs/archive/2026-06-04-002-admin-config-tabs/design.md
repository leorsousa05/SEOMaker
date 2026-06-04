# Design: Tela de Configuração com Abas

## Architecture

### Frontend
- Tabs controladas por CSS + micro-JS (radio buttons ou class toggle)
- Cada tab renderiza um grupo de campos
- Indicador visual de tab ativa
- Mobile: tabs horizontal scroll ou accordion

### Backend
- Nenhuma mudança em controllers
- SettingsController continua recebendo todos os campos via POST
- Opcional: definir grupos no controller para passar à view

## Tab Groups

| Tab | Settings |
|-----|----------|
| Geral | site_title, site_description, site_url, site_logo, favicon |
| SEO | meta_default_title, meta_default_description, og_image, robots_txt_custom |
| Contato | contact_email, contact_phone, contact_address, contact_whatsapp |
| Email | mail_from, mail_from_name, smtp_host, smtp_port, smtp_user, smtp_pass |
| Redes Sociais | facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url |

## Contracts

```php
// SettingsController: passar grupos para view
$groups = [
    'geral' => ['site_title', 'site_description', 'site_url', 'site_logo', 'favicon'],
    'seo' => ['meta_default_title', 'meta_default_description', 'og_image', 'robots_txt_custom'],
    'contato' => ['contact_email', 'contact_phone', 'contact_address', 'contact_whatsapp'],
    'email' => ['mail_from', 'mail_from_name', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass'],
    'social' => ['facebook_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'youtube_url'],
];
```

## Routes
Nenhuma mudança — GET|POST `/admin/settings`.

## CSS Pattern
- `.tabs-nav` — lista horizontal de botões
- `.tabs-panel` — conteúdo de cada aba, escondido por padrão
- `.tabs-panel.active` — visível
- Transição suave de opacidade
