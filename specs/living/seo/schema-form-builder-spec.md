# Spec: SchemaFormBuilder

## ADDED

### Seo\SchemaFormBuilder

#### Métodos
```php
public static function types(): array;
public static function fieldsForType(string $type): array;
public static function buildJson(array $postData, string $type): string;
public static function parseJson(string $json, string $type): array;
```

#### Tipos Suportados
| Tipo | Campos |
|------|--------|
| WebPage | name, description |
| Organization | name, url, logo, telephone, email |
| LocalBusiness | name, telephone, address, openingHours, priceRange |
| ContactPage | name, telephone, email, address |
| AboutPage | name, description |
| Service | name, description, provider, areaServed |
| FAQPage | (array de Question/Answer) |
| Article | headline, author, datePublished, image |
| Product | name, description, brand, price, currency |

#### Dot Notation
Campos usam notação de ponto para nested objects:
- `contactPoint.telephone` → `['contactPoint' => ['telephone' => $val]]`
- `address.addressLocality` → `['address' => ['addressLocality' => $val]]`

#### buildJson
1. Pega fieldsForType do tipo
2. Extrai valores do POST
3. Monta array nested usando dot notation
4. Adiciona `@context` e `@type`
5. Retorna JSON string

#### parseJson
1. Decodifica JSON
2. Para cada campo definido, extrai valor usando dot notation
3. Retorna array `[campo => valor]`
