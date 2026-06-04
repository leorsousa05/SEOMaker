# Design: Admin Leigo-Friendly + Schema Visual Editor

## Architecture

### Schema Editor
Cada tipo de schema tem um "form builder" que define campos:
```php
'schemaFields' => [
    'Organization' => [
        ['name' => 'org_name', 'label' => 'Nome da Organização', 'type' => 'text', 'key' => 'name'],
        ['name' => 'org_phone', 'label' => 'Telefone', 'type' => 'tel', 'key' => 'contactPoint.telephone'],
        ['name' => 'org_email', 'label' => 'Email', 'type' => 'email', 'key' => 'contactPoint.email'],
        ['name' => 'org_address', 'label' => 'Endereço', 'type' => 'textarea', 'key' => 'address.addressLocality'],
    ],
    'LocalBusiness' => [
        ['name' => 'biz_name', 'label' => 'Nome do Negócio', 'type' => 'text', 'key' => 'name'],
        ['name' => 'biz_phone', 'label' => 'Telefone', 'type' => 'tel', 'key' => 'telephone'],
        ['name' => 'biz_hours', 'label' => 'Horário de Funcionamento', 'type' => 'text', 'key' => 'openingHours', 'placeholder' => 'Mo-Fr 09:00-18:00'],
    ],
]
```

### Data Flow
1. Admin seleciona schema_type
2. JS mostra campos do formulário correspondente
3. Admin preenche campos leigos
4. No submit, PHP converte campos → array JSON-LD → salva em schema_data
5. Na edição, PHP faz parse do JSON e preenche os campos

### Settings Simplificadas
- `site_url` → campo obrigatório com validação
- `analytics_id` → placeholder explicativo
- `contact_email` → type=email
- `og_image` → helper text explicando dimensões ideais
- Novo campo: `business_type` → select com tipos de negócio

### SEO Preview
- Card visual simulando resultado do Google
- Atualiza em tempo real (JS) ou no load

## Routes
Sem mudanças nas rotas — mesmo endpoints, payload diferente.

## JS Components
- `schemaEditor.js` — troca campos por tipo, gera JSON no submit
- `seoPreview.js` — atualiza preview Google
