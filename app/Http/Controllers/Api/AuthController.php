<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // ACTUALIZAR last_login_at CORRECTAMENTE
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        // Recargar el usuario para obtener los atributos actualizados
        $user->refresh();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('auth-token')->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user()->load(['projects', 'assignedTasks']);
        
        return response()->json(new UserResource($user));
    }
}