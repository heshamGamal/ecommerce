<?php
namespace App\Infrastructure\Repositories\Eloquent;
use App\Domain\Contracts\Repositories\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
class PermissionRepository implements PermissionRepositoryInterface
{
    public function all(): iterable { return Permission::orderBy('name')->get(); }
    public function findByNames(array $names): iterable { return Permission::whereIn('name', $names)->where('guard_name', 'web')->get(); }
}
