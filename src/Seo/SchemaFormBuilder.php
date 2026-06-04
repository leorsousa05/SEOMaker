<?php

declare(strict_types=1);

namespace App\Seo;

class SchemaFormBuilder
{
    /**
     * @return array<string, array<int, array<string, string>>>
     */
    public static function fieldsForType(string $type): array
    {
        $fields = [
            'WebPage' => [
                ['name' => 'schema_name', 'label' => 'Nome da Página', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_description', 'label' => 'Descrição', 'type' => 'textarea', 'key' => 'description'],
            ],
            'WebSite' => [
                ['name' => 'schema_name', 'label' => 'Nome do Site', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_url', 'label' => 'URL do Site', 'type' => 'url', 'key' => 'url'],
            ],
            'Organization' => [
                ['name' => 'schema_name', 'label' => 'Nome da Organização', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_url', 'label' => 'Website', 'type' => 'url', 'key' => 'url'],
                ['name' => 'schema_logo', 'label' => 'URL do Logo', 'type' => 'url', 'key' => 'logo'],
                ['name' => 'schema_phone', 'label' => 'Telefone', 'type' => 'tel', 'key' => 'contactPoint.telephone'],
                ['name' => 'schema_email', 'label' => 'Email', 'type' => 'email', 'key' => 'contactPoint.email'],
            ],
            'LocalBusiness' => [
                ['name' => 'schema_name', 'label' => 'Nome do Negócio', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_phone', 'label' => 'Telefone', 'type' => 'tel', 'key' => 'telephone'],
                ['name' => 'schema_address', 'label' => 'Endereço', 'type' => 'textarea', 'key' => 'address.addressLocality'],
                ['name' => 'schema_hours', 'label' => 'Horário de Funcionamento', 'type' => 'text', 'key' => 'openingHours', 'placeholder' => 'Ex: Mo-Fr 09:00-18:00'],
                ['name' => 'schema_price', 'label' => 'Faixa de Preço', 'type' => 'text', 'key' => 'priceRange', 'placeholder' => 'Ex: $$ ou €€'],
            ],
            'ContactPage' => [
                ['name' => 'schema_name', 'label' => 'Nome', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_phone', 'label' => 'Telefone', 'type' => 'tel', 'key' => 'mainEntity.telephone'],
                ['name' => 'schema_email', 'label' => 'Email', 'type' => 'email', 'key' => 'mainEntity.email'],
                ['name' => 'schema_address', 'label' => 'Endereço', 'type' => 'textarea', 'key' => 'mainEntity.address.addressLocality'],
            ],
            'AboutPage' => [
                ['name' => 'schema_name', 'label' => 'Nome', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_description', 'label' => 'Descrição', 'type' => 'textarea', 'key' => 'description'],
            ],
            'Service' => [
                ['name' => 'schema_name', 'label' => 'Nome do Serviço', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_description', 'label' => 'Descrição', 'type' => 'textarea', 'key' => 'description'],
                ['name' => 'schema_provider', 'label' => 'Provedor', 'type' => 'text', 'key' => 'provider.name'],
                ['name' => 'schema_area', 'label' => 'Área de Atuação', 'type' => 'text', 'key' => 'areaServed'],
            ],
            'FAQPage' => [
                ['name' => 'schema_faq_json', 'label' => 'Perguntas e Respostas (JSON)', 'type' => 'textarea', 'key' => 'mainEntity', 'placeholder' => '[{"@type":"Question","name":"Pergunta?","acceptedAnswer":{"@type":"Answer","text":"Resposta."}}]'],
            ],
            'Article' => [
                ['name' => 'schema_headline', 'label' => 'Título do Artigo', 'type' => 'text', 'key' => 'headline'],
                ['name' => 'schema_author', 'label' => 'Autor', 'type' => 'text', 'key' => 'author.name'],
                ['name' => 'schema_date', 'label' => 'Data de Publicação', 'type' => 'date', 'key' => 'datePublished'],
                ['name' => 'schema_image', 'label' => 'URL da Imagem', 'type' => 'url', 'key' => 'image'],
            ],
            'Product' => [
                ['name' => 'schema_name', 'label' => 'Nome do Produto', 'type' => 'text', 'key' => 'name'],
                ['name' => 'schema_description', 'label' => 'Descrição', 'type' => 'textarea', 'key' => 'description'],
                ['name' => 'schema_brand', 'label' => 'Marca', 'type' => 'text', 'key' => 'brand.name'],
                ['name' => 'schema_price', 'label' => 'Preço', 'type' => 'number', 'key' => 'offers.price'],
                ['name' => 'schema_currency', 'label' => 'Moeda', 'type' => 'text', 'key' => 'offers.priceCurrency', 'placeholder' => 'BRL'],
            ],
        ];
        
        return $fields[$type] ?? [];
    }
    
    /**
     * @return array<int, string>
     */
    public static function types(): array
    {
        return [
            'WebPage', 'WebSite', 'Organization', 'LocalBusiness',
            'ContactPage', 'AboutPage', 'Service', 'FAQPage',
            'Article', 'Product',
        ];
    }
    
    /**
     * @param array<string, mixed> $postData
     */
    public static function buildJson(array $postData, string $type): string
    {
        $fields = self::fieldsForType($type);
        $data = ['@context' => 'https://schema.org', '@type' => $type];
        
        foreach ($fields as $field) {
            $key = $field['key'];
            $value = $postData[$field['name']] ?? '';
            
            if ($value === '') {
                continue;
            }
            
            self::setNestedValue($data, $key, $value);
        }
        
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @return array<string, string>
     */
    public static function parseJson(string $json, string $type): array
    {
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            $decoded = [];
        }
        
        $fields = self::fieldsForType($type);
        $values = [];
        
        foreach ($fields as $field) {
            $values[$field['name']] = self::getNestedValue($decoded, $field['key']) ?? '';
        }
        
        return $values;
    }
    
    /**
     * @param array<string, mixed> $array
     */
    private static function setNestedValue(array &$array, string $path, mixed $value): void
    {
        $keys = explode('.', $path);
        $current = &$array;
        
        foreach ($keys as $i => $key) {
            if ($i === count($keys) - 1) {
                $current[$key] = $value;
            } else {
                if (!isset($current[$key]) || !is_array($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
            }
        }
    }
    
    /**
     * @param array<string, mixed> $array
     */
    private static function getNestedValue(array $array, string $path): mixed
    {
        $keys = explode('.', $path);
        $current = $array;
        
        foreach ($keys as $key) {
            if (!is_array($current) || !array_key_exists($key, $current)) {
                return null;
            }
            $current = $current[$key];
        }
        
        return $current;
    }
}
