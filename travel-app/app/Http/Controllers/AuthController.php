<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->sendError(
                'Unauthorized.',
                'These credentials do not match our records.',
                401
            );
        }

        $token = $user->createToken('authToken')->plainTextToken;
        return $this->sendResponse(
            [
                'user' => $user,
                'token' => $token,
            ],
            'Login Success'
        );
    }
}
