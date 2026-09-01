<?php
namespace App\Domain\Contracts\Repositories;
use Spatie\Permission\Models\Permission;
interface PermissionRepositoryInterface
{
    public function all(): iterable;
    public function findByNames(array $names): iterable;
}
