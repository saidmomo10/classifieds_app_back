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
        ]);

        $user->update($request->all());
        $user->save();

        return response()->json(['success' => 'Image modifiée avec succès', 'user' => $user]);
    }

    public function complete(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'ifu' => 'required|string|max:100',
            'rccm' => 'required|string|max:100',
            'website' => 'required|url|max:255',
        ]);

        $user->update([
            'ifu' => $request->ifu,
            'rccm' => $request->rccm,
            'website' => $request->website,
        ]);

        return response()->json([
            'success' => 'Profil modifié avec succès',
            'user' => $user
        ]);
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
