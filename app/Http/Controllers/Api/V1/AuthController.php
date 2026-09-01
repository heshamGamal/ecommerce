<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domain\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::create($data);
        return response()->json(['status' => 'success', 'data' => ['user' => $user, 'token' => $user->createToken('api')->plainTextToken]], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'بيانات الدخول غير صحيحة.'], 401);
        }
        return response()->json(['status' => 'success', 'data' => ['user' => $user, 'token' => $user->createToken('api')->plainTextToken]]);
    }

    public function me(Request $request): JsonResponse { return response()->json(['status' => 'success', 'data' => $request->user()]); }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['status' => 'success', 'message' => 'تم تسجيل الخروج بنجاح.']);
    }
}
