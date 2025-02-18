<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name', 'email']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        // $user = $request->user();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required',
            'avatar' => 'required'
        ]);

        // Si une nouvelle image est téléversée
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Enregistrer la nouvelle image
            $imagePath = $request->file('avatar')->store('avatar', 'public');
            $user->avatar = $imagePath;
        }

        $user->save();

        return response()->json(['success' => 'Image modifiée avec succès', 'user' => $user]);
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
