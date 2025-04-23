<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Rediriger l'utilisateur vers Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account']) // Forcer le choix du compte
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser->getEmail()) {
                return response()->json(['error' => 'Adresse email non récupérée depuis Google.'], 400);
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(24))
                ]);
            } elseif (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            // Générer le token
            $token = $user->createToken('GoogleAuth')->plainTextToken;

            // Définir l'URL de redirection (frontend)
            $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:5173'));

            // Rediriger avec le token en paramètre
            return redirect()->to("{$frontendUrl}/auth/callback?token={$token}&user_id={$user->id}");

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur d\'authentification Google', 'message' => $e->getMessage()], 500);
        }
    }

}
