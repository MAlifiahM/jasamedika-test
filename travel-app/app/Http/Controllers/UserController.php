<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $user = User::find(auth()->user()->id);

        if (!$user) {
            return $this->sendError('User not found', []);
        }

        return $this->sendResponse(
            [
                'user' => new UserResource($user),
                'id' => auth()->user()->id
            ],
            'User login profile'
        );
    }
}
