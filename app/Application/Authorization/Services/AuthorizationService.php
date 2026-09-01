<?php
namespace App\Application\Authorization\Services;
use App\Application\Authorization\DTOs\RoleData;
use App\Domain\Contracts\Repositories\PermissionRepositoryInterface;
use App\Domain\Contracts\Repositories\RoleRepositoryInterface;
use App\Domain\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;
class AuthorizationService
{
    public function __construct(private readonly RoleRepositoryInterface $roles, private readonly PermissionRepositoryInterface $permissions) {}
    public function roles(): iterable { return $this->roles->all(); }
    public function createRole(RoleData $data): Role
    {
        return DB::transaction(function () use ($data): Role {
            $found = collect($this->permissions->findByNames($data->permissions));
            if ($found->count() !== count(array_unique($data->permissions))) throw new InvalidArgumentException('إحدى الصلاحيات المطلوبة غير موجودة.');
            return $this->roles->create($data->name, $found->all());
        });
    }
    public function assignRole(User $user, string $role): User { return DB::transaction(fn () => tap($user)->assignRole($this->roles->findByName($role))); }
    public function revokeRole(User $user, string $role): User { return DB::transaction(fn () => tap($user)->removeRole($role)); }
    public function syncPermissions(Role $role, array $permissions): Role
    {
        return DB::transaction(function () use ($role, $permissions): Role {
            $found = collect($this->permissions->findByNames($permissions));
            if ($found->count() !== count(array_unique($permissions))) throw new InvalidArgumentException('إحدى الصلاحيات المطلوبة غير موجودة.');
            $role->syncPermissions($found->all()); return $role->load('permissions');
        });
    }
    public function deleteRole(Role $role): void
    {
        if (in_array($role->name, ['super_admin', 'admin', 'customer'], true)) throw new InvalidArgumentException('لا يمكن حذف دور أساسي في النظام.');
        DB::transaction(fn () => $this->roles->delete($role));
    }
}
