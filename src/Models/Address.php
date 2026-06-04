<?php

declare(strict_types=1);

namespace App\Models;

class Address
{
    public ?int $id = null;
    public ?int $page_id = null;
    public string $street = '';
    public string $number = '';
    public string $complement = '';
    public string $neighborhood = '';
    public string $city = '';
    public string $state = '';
    public string $zip = '';
    public string $country = 'Brasil';
    public ?string $lat = null;
    public ?string $lng = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $addr = new self();
        $addr->id = isset($data['id']) ? (int) $data['id'] : null;
        $addr->page_id = isset($data['page_id']) ? (int) $data['page_id'] : null;
        $addr->street = (string) ($data['street'] ?? '');
        $addr->number = (string) ($data['number'] ?? '');
        $addr->complement = (string) ($data['complement'] ?? '');
        $addr->neighborhood = (string) ($data['neighborhood'] ?? '');
        $addr->city = (string) ($data['city'] ?? '');
        $addr->state = (string) ($data['state'] ?? '');
        $addr->zip = (string) ($data['zip'] ?? '');
        $addr->country = (string) ($data['country'] ?? 'Brasil');
        $addr->lat = $data['lat'] ?? null;
        $addr->lng = $data['lng'] ?? null;
        $addr->created_at = $data['created_at'] ?? null;
        $addr->updated_at = $data['updated_at'] ?? null;
        return $addr;
    }
    
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
    public function fullAddress(): string
    {
        $parts = array_filter([
            $this->street,
            $this->number ? 'Nº ' . $this->number : null,
            $this->complement,
            $this->neighborhood,
            $this->city,
            $this->state,
            $this->zip,
            $this->country,
        ]);
        return implode(', ', $parts);
    }
}
