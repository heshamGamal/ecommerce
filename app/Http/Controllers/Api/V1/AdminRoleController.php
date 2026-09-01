<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domain\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function roles(): JsonResponse { return response()->json(['status' => 'success', 'data' => Role::with('permissions')->get()]); }

    public function assign(Request $request, string $user): JsonResponse
    {
        $data = $request->validate(['role' => ['required', 'string', 'exists:roles,name']]);
        $model = User::findOrFail($user);
        $model->assignRole(Role::findByName($data['role'], 'web'));
        return response()->json(['status' => 'success', 'data' => ['user' => $model->load('roles')]]);
    }

    public function revoke(Request $request, string $user): JsonResponse
    {
        $data = $request->validate(['role' => ['required', 'string', 'exists:roles,name']]);
        $model = User::findOrFail($user);
        $model->removeRole($data['role']);
        return response()->json(['status' => 'success', 'data' => ['user' => $model->load('roles')]]);
    }
}
