<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ConfirmationEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function register(Request $request)
    {
      $validate = Validator::make($request->all(), [
          'name' => 'required|string|max:250',
          'email' => 'required|string|max:250|unique:users,email',
          'password' => 'required|string|min:8|confirmed'
      ]);

      if($validate->fails()){
          return response()->json([
              'status' => 'failed',
              'message' => 'Validation Error!',
              'data' => $validate->errors(),
          ], 403);
      }

      $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password)
      ]);

      $data['token'] = $user->createToken($request->email)->plainTextToken;
      $data['user'] = $user;

      $response = [
          'status' => 'success',
          'message' => 'User is created successfully.',
          'data' => $data,
      ];

      $confirmationCode = rand(1000, 9999);
      Mail::to($user->email)->send(new ConfirmationEmail($confirmationCode));

      $user->confirmation_code = $confirmationCode;
      $user->save();

      return response()->json($response, 201);
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ], 403);  
        }

        // Check email exist
        $user = User::where('email', $request->email)->first();

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Email ou mot de passe incorrect'
                ], 401);
        }

        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = $user;
        
        $response = [
            'status' => 'success',
            'message' => 'User is logged in successfully.',
            'data' => $data,
        ];

        return response()->json($response, 200);
    } 

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
            ], 200);
    }    



    public function upload_user_photo(Request $request){
        // check if image has been received from form
        if($request->file('avatar')){
          // check if user has an existing avatar
          if($this->guard()->user()->avatar != NULL){
            // delete existing image file
            Storage::disk('user_avatars')->delete($this->guard()->user()->avatar);
          }
      
          // processing the uploaded image
          $avatar_name = $this->random_char_gen(20).'.'.$request->file('avatar')->getClientOriginalExtension();
          $avatar_path = $request->file('avatar')->storeAs('',$avatar_name, 'user_avatars');
      
          // Update user's avatar column on 'users' table
          $profile = User::find($request->user()->id);
          $profile->avatar = $avatar_path;
      
          if($profile->save()){
            return response()->json([
              'status'    =>  'success',
              'message'   =>  'Profile Photo Updated!',
              'avatar_url'=>  url('storage/user-avatar/'.$avatar_path)
            ]);
          }else{
            return response()->json([
              'status'    => 'failure',
              'message'   => 'failed to update profile photo!',
              'avatar_url'=> NULL
            ]);
          }
      
        }
      
        return response()->json([
          'status'    => 'failure',
          'message'   => 'No image file uploaded!',
          'avatar_url'=> NULL
        ]);
      }


      public function confirm(Request $request)
    {

      $request->validate([
        'email' => 'required|string|email',
        'confirmation_code' => 'required|string'
      ]);
        // Valider les données de la demande
      //   $validate = Validator::make($request->all(), [
      //     'email' => 'required|string|email',
      //     'confirmation_code' => 'required|string'
      // ]);

      //   if($validate->fails()){
      //     return response()->json([
      //         'status' => 'failed',
      //         'message' => 'Validation Error!',
      //         'data' => $validate->errors(),
      //     ], 403);  
      // }

        // Récupérer l'utilisateur par son adresse e-mail
        $user = User::where('email', $request->email)->first();
        // return $user;

        // Vérifier si l'utilisateur existe et si le code de confirmation est correct
        if ($user && $user->confirmation_code == $request->confirmation_code) {
            // Mettre à jour le statut de confirmation de l'utilisateur
            $user->email_verified_at = now();
            $user->confirmation_code = null; // Optionnel : Effacer le code de confirmation après validation
            $user->save();

            // Retourner une réponse de succès
            return response()->json(['message' => 'Confirmation successful'], 200);
        }else{
          // Retourner une réponse d'erreur en cas d'échec de validation
          return response()->json(['error' => 'Invalid confirmation code'], 422);
        }

        
    }

    
}