<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name', 'email']);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $validatedData = $request->validated();
        
        $user->update($validatedData);
        $user = $user->refresh();

        $success['user'] = $user;
        $success['success'] = 'Profile mis à jour';

        return response()->json($success, 201);
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|min:8',
        ]);

        if (!password_verify($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
}
