<?php
namespace App\Infrastructure\Repositories\Eloquent;
use App\Domain\Contracts\Repositories\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
class RoleRepository implements RoleRepositoryInterface
{
    public function all(): iterable { return Role::with('permissions')->orderBy('name')->get(); }
    public function findByName(string $name): Role { return Role::findByName($name, 'web'); }
    public function create(string $name, array $permissions = []): Role { $role = Role::create(['name' => $name, 'guard_name' => 'web']); $role->syncPermissions($permissions); return $role->load('permissions'); }
    public function delete(Role $role): void { $role->delete(); }
}
