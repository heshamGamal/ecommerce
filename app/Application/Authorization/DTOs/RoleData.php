<?php
namespace App\Application\Authorization\DTOs;
final class RoleData
{
    public function __construct(public readonly string $name, public readonly array $permissions = []) {}
    public static function fromArray(array $data): self { return new self(trim((string) ($data['name'] ?? '')), array_values(array_filter($data['permissions'] ?? [], 'is_string'))); }
}
