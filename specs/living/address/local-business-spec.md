# Spec: Endereço e Geolocalização

## ADDED

### Database: addresses
```sql
CREATE TABLE addresses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    page_id INTEGER,
    street TEXT NOT NULL,
    number TEXT,
    complement TEXT,
    neighborhood TEXT,
    city TEXT NOT NULL,
    state TEXT NOT NULL,
    zip TEXT,
    country TEXT DEFAULT 'Brasil',
    lat TEXT,
    lng TEXT,
    created_at TEXT,
    updated_at TEXT
);
```

### Models\Address
```php
class Address {
    public ?int $id;
    public ?int $page_id;
    public string $street = '';
    public string $number = '';
    public string $complement = '';
    public string $neighborhood = '';
    public string $city = '';
    public string $state = '';
    public string $zip = '';
    public string $country = 'Brasil';
    public ?string $lat;
    public ?string $lng;
}
```

### src/Seo/LocalBusinessSchema.php
- `generate(Address $addr, array $extra = []): array` — retorna array LocalBusiness
- Inclui: name, address (PostalAddress), geo (GeoCoordinates), telephone, openingHours

### templates/admin/_address_form.php
- Campos em grid 2-col:
  - Rua (full width)
  - Número + Complemento
  - Bairro + Cidade
  - Estado + CEP
  - País
  - Lat/Lng (opcional, avançado)
- Preenche schema_data automaticamente ao salvar

### src/Admin/PagesController.php (modificado)
- Save: se campos de endereço presentes, salva/upsert na tabela addresses
- Edit: carrega address da página se existir
- Delete: cascade delete address

### Render Frontend
- Block `map` usa address para gerar embed Google Maps
- SeoManager inclui LocalBusiness schema se página tem address
