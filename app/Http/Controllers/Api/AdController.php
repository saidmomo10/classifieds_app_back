<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\AnnouncementPublished;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewAnnouncementNotification;

class AdController extends Controller
{
    public function index(){
        $ads = Ad::with('images', 'subcategory', 'user', 'department', 'city')
                ->orderBy('created_at', 'desc') // Ordonner par date de création décroissante
                ->paginate(6);

        return $ads;
    }


    public function getAds(Request $request){
        $data = Ad::where('title', 'LIKE','%'.$request->keyword.'%')
        ->orWhere('description', 'LIKE','%'.$request->keyword.'%')
        ->with('images', 'subcategory', 'user', 'department', 'city')
        ->orderBy('created_at', 'desc')->get();
        return response()->json($data); 
    }

    public function getByDepartment($department_id) {
        $annonces = Ad::where('department_id', $department_id)->with('images', 'subcategory', 'user', 'department', 'city')->orderBy('created_at', 'desc')->get();
        $number = $annonces->count();
        return response()->json($annonces);
    }

    public function myAds(){
        $user = auth('sanctum')->user();
        $all = Ad::with('subcategory', 'images')->where('user_id', $user->id)->get();
        $onSale = $all->filter(function ($ad) {
            return $ad->sold === 'En cours de vente';
        });
        $sale = $all->filter(function ($ad) {
            return $ad->sold === 'Vendu';
        });
        
        return ['all' => $all, 'onSale' => $onSale, 'sale' => $sale];
    }

    public function searchAds(Request $request){
        $ads = Ad::with('images', 'subcategory', 'user', 'department', 'city')->orderBy('created_at', 'desc')->get();
        return response()->json($ads);
    }

    public function search(Request $request){
        $ads=Ad::where('title',$request->keywords)->get();
        return response()->json($ads);    
    }

    public function show($id){
        $ad = Ad::with('images', 'subcategory', 'user', 'department', 'city')->find($id);
        
        $comment = Comment::with('user', 'ad')->where('ad_id', $ad->id)->get();

        $ad->increment('views');

        return ['comment' => $comment, 'ad' => $ad];
    }

    public function mostVisitedAds(){
        if(auth()->user()->hasActiveSubscriptionType('Free')){
            $ads = Ad::with('images', 'subcategory', 'user', 'department', 'city')->orderByDesc('views')->limit(5)->get();
            return response()->json($ads);
        }else{
            return response()->json(['error' => 'Fonctionnalité réservée aux abonnés Free'], 403);
        }
    }

    public function store(Request $request){

        $user = Auth::user();
        $admin = $user->hasRole('Admin');
        $key = $user->subscriptions()->where('status', 'Abonnement actif')->latest('activated_at')->first();
        $expire = $user->subscriptions()->where('status', 'Abonnement expire')->latest('activated_at')->first();
    
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'delivery_status' => 'required',
            'state' => 'required',
            'price_type' => 'required',
            'phone' => 'required',
            'subcategory_id' => 'required',
            'department_id' => 'required',
            'city_id' => 'required',
            'images.*' => [
                'required',
                'image',
                function ($attribute, $value, $fail) {
                    if (!$value->isValid()) {
                        $fail('Une erreur est survenue lors du téléchargement de l\'image.');
                    }
                },
            ],
        ];
    
        if (!$admin) {
            $rules['user_subscription_id'] = 'required';
    
            if ($key) {
                $rules['images'][] = 'max:' . $key->max_images;
            } else if ($expire) {
                $rules['images'][] = 'max:3';
            }
        }
    
        $validation = $request->validate($rules);
    
        if ($admin) {
            $request->merge(['user_id' => $user->id]);
    
            // Créer l'annonce
            $ad = Ad::create([
                'title' => $request->title,
                'price' => $request->price,
                'phone' => $request->phone,
                'price_type' => $request->price_type,
                'delivery_status' => $request->delivery_status,
                'state' => $request->state,
                'description' => $request->description,
                'department_id' => $request->department_id,
                'city_id' => $request->city_id,
                'user_id' => $user->id,
                'subcategory_id' => $request->subcategory_id,
            ]);
    
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');
                $ad->images()->create(['path' => $imagePath]);
            }
    
            return response()->json('Annonce créée avec succès', 201);
        } else {
            // Logique pour les utilisateurs normaux (avec abonnement)
            if ($key && $key->pivot) {
                if ($key->pivot) {
                    $use = Ad::where('user_subscription_id', $key->pivot->id)->count();
    
                    if ($use >= $key->max_ads) {
                        return response()->json(['message' => 'Vous avez épuisé votre quota'], 404);
                    } else {
                        $request->merge(['user_id' => $user->id, 'user_subscription_id' => $key->id]);
    
                        $ad = Ad::create([
                            'title' => $request->title,
                            'price' => $request->price,
                            'phone' => $request->phone,
                            'price_type' => $request->price_type,
                            'delivery_status' => $request->delivery_status,
                            'state' => $request->state,
                            'description' => $request->description,
                            'department_id' => $request->department_id,
                            'city_id' => $request->city_id,
                            'user_id' => $user->id,
                            'subcategory_id' => $request->subcategory_id,
                            'user_subscription_id' => $key->pivot->id,
                        ]);
    
                        foreach ($request->file('images') as $image) {
                            $imagePath = $image->store('images', 'public');
                            $ad->images()->create(['path' => $imagePath]);
                        }
                        
                        $admin = User::role('Admin')->get();
                        // Notifiez chaque administrateur
                        foreach ($admin as $admi) {
                            $admi->notify(new NewAnnouncementNotification($ad, $user));
                        }

                        // Déclencher l'événement
                        event(new AnnouncementPublished($ad, $user));
                        return response()->json('Annonce créée avec succès', 201);
                        
                        // // Notifie tous les admins
                        // $admin = $user->hasRole('Admin');
                        // foreach ($admins as $admin) {
                        //     $admin->notify(new NewAdCreated($ad));
                        // }
                    }
                } else {
                    return response()->json(['message' => 'Aucun abonnement'], 404);
                }
            } else{
                return response()->json(['message' => 'Aucun abonnement'], 404);
            }
        }
    }
    

    public function update($id, Request $request){
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
        $admin = $user->hasRole('Admin');

        // Récupérer l'annonce à mettre à jour
        $ad = Ad::findOrFail($id);

        // Vérifier si l'utilisateur possède l'annonce
        if ($ad->user_id !== $user->id) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette annonce.'], 403);
        }

        // Définir les règles de validation pour la requête
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            // 'user_subscription_id' => 'required',
            'subcategory_id' => 'required',
            'department_id' => 'required',
            'city_id' => 'required',
            // 'images.*' => [
            //     'required',
            //     'image',
            //     function ($attribute, $value, $fail) {
            //         if (!$value->isValid()) {
            //             $fail('Une erreur est survenue lors du téléchargement de l\'image.');
            //         }
            //     },
            // ],
        ];

        if (!$admin) {
            $rules['user_subscription_id'] = 'required';
    
            if ($key) {
                $rules['images'][] = 'max:' . $key->max_images;
            } else if ($expire) {
                $rules['images'][] = 'max:3';
            }
        }

        // Valider la requête
        $validation = $request->validate($rules);

        // Mettre à jour les données de l'annonce avec les nouvelles données de la requête
        $ad->update([
            'title' => $request->title,
            'description' => $request->description,
            'subcategory_id' => $request->subcategory_id,
            'department_id' => $request->department_id,
            'city_id' => $request->city_id,
        ]);

        // Retourner une réponse JSON indiquant que l'annonce a été mise à jour avec succès
        return response()->json('Annonce mise à jour avec succès', 200);
    }

    public function delete($id)
    {
        $ad = Ad::findOrFail($id);
        $result = $ad->delete();

        if($result){
            return ["result" => "supprimé"];
        }
        else{
            return ["result" => "erreur"];
        }
    }

    


    public function updateSold(Request $request, $id){
        $user = auth('sanctum')->user();    
        $ad = Ad::where('user_id', $user->id)->find($id);
        
        if($ad !== null) {
            $ad->sold = 'Vendu';
            $ad->save();
            return $ad->sold;
        } else {
            return response()->json(['error' => 'Ad not found or does not belong to the authenticated user'], 404);
        }

    }

    public function getAdUser(){
        $user = auth('sanctum')->user();    
        // $ad = Ad::where('user_id', $user->id);
        
        return response()->json($user);

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
    //         'department' => $request->department,
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