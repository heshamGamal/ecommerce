<?php
namespace App\Domain\Contracts\Repositories;
use Spatie\Permission\Models\Role;
interface RoleRepositoryInterface
{
    public function all(): iterable;
    public function findByName(string $name): Role;
    public function create(string $name, array $permissions = []): Role;
    public function delete(Role $role): void;
}
