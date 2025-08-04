<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:User,Email',
            'PasswordHash' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = new User();
        $user->FirstName = $request->FirstName;
        $user->LastName = $request->LastName;
        $user->Email = $request->Email;
        $user->PasswordHash = Hash::make($request->PasswordHash);
        $user->Role = $request->Role ?? null;
        $user->Department = $request->Department ?? null;
        $user->IsActive = $request->IsActive ?? false;
        $user->ProfileImageUrl = $request->ProfileImageUrl ?? null;
        $user->LastSeenDate = $request->LastSeenDate ?? now();
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User successfully registered',
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'Email' => 'required|email',
            'PasswordHash' => 'required|string',
        ]);

        $user = User::where('Email', $request->Email)->first();

        if (!$user || !Hash::check($request->PasswordHash, $user->PasswordHash)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = JWTAuth::claims(['sub' => $user->id])->fromUser($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
