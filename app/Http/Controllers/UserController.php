<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|email|unique:User,Email',
            'PasswordHash' => 'required|string',
            'Role' => 'nullable|string',
            'Department' => 'nullable|string',
            'IsActive' => 'boolean',
            'ProfileImageUrl' => 'nullable|string',
            'LastSeenDate' => 'nullable|date',
            'CreatedDate' => 'nullable|date',
            'UpdatedDate' => 'nullable|date'
        ]);

        $user = User::create($validated);
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return $user ? response()->json($user) : response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $validated = $request->validate([
            'FirstName' => 'sometimes|required|string|max:255',
            'LastName' => 'sometimes|required|string|max:255',
            'Email' => 'sometimes|required|email|unique:User,Email,' . $id,
            'PasswordHash' => 'sometimes|required|string',
            'Role' => 'nullable|string',
            'Department' => 'nullable|string',
            'IsActive' => 'boolean',
            'ProfileImageUrl' => 'nullable|string',
            'LastSeenDate' => 'nullable|date',
            'CreatedDate' => 'nullable|date',
            'UpdatedDate' => 'nullable|date'
        ]);

        $user->update($validated);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $user = User::find($id);
    if (!$user) return response()->json(['message' => 'User not found'], 404);

    $user->forceDelete();  // ← this guarantees permanent deletion
    return response()->json(['message' => 'User deleted']);
}
}
