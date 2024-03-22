<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index(){
        $ads = Ad::with('images', 'subcategory')->paginate(5);

        return $ads;
    }

    public function searchAds(Request $request){
        $ads = Ad::with('images', 'subcategory', 'user')->get();
        return response()->json($ads);
    }

    public function search(Request $request)
    {
        $ads=Ad::where('title',$request->keywords)->get();
        return response()->json($ads);
         
    }

    public function show($id)
{
    $ad = Ad::with('images', 'subcategory', 'user')->find($id);

    return response()->json($ad);
}

    public function store(Request $request){

        $user = Auth::user();
        // $key = $user->subscriptions()->where('status', 'Abonnement actif')->first();
        $key = $user->subscriptions()->where('status', 'Abonnement actif')->latest('activated_at')->first();
        $expire = $user->subscriptions()->where('status', 'Abonnement expire')->latest('activated_at')->first();

        // $key = $user->subscriptions()->latest('activated_at')->first();



        $rules = [
            'title' => 'required',
            'description' => 'required',
            'country' => 'required',
            // 'city' => 'required',
            // 'price' => 'required',
            // 'delivery_status' => 'required',
            // 'state' => 'required',
            // 'price_type' => 'required',
            // 'phone' => 'required',
            'user_id' => 'required',
            'user_subscription_id' => 'required',
            'subcategory_id' => 'required',
            'images.*' => [
                'required',
                'image',
                function ($attribute, $value, $fail) {
                    // Vérifier si une erreur est survenue lors du téléchargement de l'image
                    if (!$value->isValid()) {
                        $fail('Une erreur est survenue lors du téléchargement de l\'image.');
                    }
                },
            ],
        ];
        
        // Ajouter une règle de validation pour limiter le nombre total d'images
        if ($key) {
            $rules['images'][] = 'max:' . $key->max_images;
        } else if($expire){
            $rules['images'][] = 'max:3';
        }
        
        $validation = $request->validate($rules);
 
        //  $mainly_picture = null;
        //  $secondary_picture = null;
        //  $tertiary_picture = null;
    
        //  if ($request->hasFile('image')) {
        //      if ($request->file('image')[0]) {
        //          $mainly_picture = Storage::disk()->put('mainly_pictures', $request->file('image')[0]);
        //      }
        //      if ($request->file('image')[1]) {
        //          $secondary_picture = Storage::disk()->put('secondary_pictures', $request->file('image')[1]);
        //      }
        //      if ($request->file('image')[2]) {
        //          $tertiary_picture = Storage::disk()->put('tertiary_pictures', $request->file('image')[2]);
        //      }
        //  }

        // $mainly_picture = Storage::disk()->put('mainly_pictures', $request->file('image1'));
        // $secondary_picture = Storage::disk()->put('secondary_pictures', $request->file('image2'));
        // $tertiary_picture = Storage::disk()->put('tertiary_pictures', $request->file('image3'));
 
         //dd($picture);

        if ($key) {
            if (Auth::check()) {

                if($key->pivot){
                    $use = Ad::where('user_subscription_id', $key->pivot->id)->count();

                    if($use >= $key->max_ads){
                        return response()->json(['message' => 'Vous avez épuisé votre quota'], 404);
                    }else{
                        $request->merge(['user_id' => $user->id]);
                        $request->merge(['user_subscription_id' => $key->id]);
    
                        // Créer l'annonce et capturer l'objet créé
                        $ad = Ad::create([
                            'title' => $request->title,
                            // 'price' => $request->price,
                            'country' => $request->country,
                            // 'phone' => $request->phone,
                            // 'price_type' => $request->price_type,
                            // 'city' => $request->city,
                            // 'delivery_status' => $request->delivery_status,
                            // 'state' => $request->state,
                            'description' => $request->description,
                            'user_id' => $user->id,
                            'subcategory_id' => $request->subcategory_id,
                            'user_subscription_id' => $key->pivot->id
                        ]);
                    
                        // Boucle foreach pour traiter chaque image
                        foreach ($request->file('images') as $image) {
                            $imagePath = $image->store('images','public');
                    
                            // Associer chaque image à l'annonce créée
                            $ad->images()->create(['path' => $imagePath]);
                        }
                    
                        // Retourner l'annonce créée avec le code de statut 201
                        return response()->json('Annonce créée avec succès', 201);
                    }
                }else{
                    return response()->json(['message' => 'Aucun abonnement'], 404);
                }
                



                
            } else {
                // Retourner une réponse d'erreur si l'utilisateur n'est pas authentifié
                return response()->json("erreur");
            }
        }else if($expire || $expire->pivot == null){
            return response()->json("Aucun abonnement");
        }


         
        
         

         //dd($request);
         
     }





    //  public function stepOne(Request $request)
    // {
    //     // Validez les données de la première étape si nécessaire
    //     $validatedData = $request->validate([
    //         'title' => 'required|string',
    //         // 'subcategory_id' => "required"
    //     ]);

        
    //     return response()->json(['data' => $validatedData]);
    // }

    // public function stepTwo(Request $request)
    // {
    //     // Validez les données de la deuxième étape si nécessaire
    //     $validatedData = $request->validate([
    //         'price' => 'required|string',
    //         'country' => $request->country,
    //         'city' => $request->city,
    //         'description' => $request->description,
    //         'delivery_status' => $request->delivery_status,
    //         // 'image1' => "required|image",
    //         // 'image2' => "required|image",
    //         // 'image3' => "required|image",
    //         // Autres règles de validation pour la deuxième étape
    //     ]);

    //     // $mainly_picture = null;
    //     // $secondary_picture = null;
    //     // $tertiary_picture = null;

    //     // $mainly_picture = Storage::disk()->put('mainly_pictures', $request->file('image1'));
    //     // $secondary_picture = Storage::disk()->put('secondary_pictures', $request->file('image2'));
    //     // $tertiary_picture = Storage::disk()->put('tertiary_pictures', $request->file('image3'));

    //     return response()->json(['data' => $validatedData]);
    // }

    // public function stepThree(Request $request)
    // {
    //     // Validez les données de la troisième étape si nécessaire
    //     $validatedData = $request->validate([
    //         // Règles de validation pour la troisième étape
    //     ]);


    //     $formData = Ad::create($validatedData);

    //     return response()->json(['data' => $formData]);
    // }
}
