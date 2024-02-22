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
        return Ad::all();
    }

    public function store(Request $request){

        $user = Auth::user();

        $validation =  $request->validate([
            'title' => "required",
            'description' => "required",
            'country' => "required",
            'city' => "required",
            'price' => "required",
            'delivery_status' => "required",
            'state' => "required",
            'price_type' => "required",
            'phone' => "required",
            'user_id' => "required",
            // 'subcategory_id' => "required",
            'images.*' => [
                'required',
                'image',
                function ($attribute, $value, $fail) {
                    // Vérifier si une erreur est survenue lors du téléchargement de l'image
                    if (!$value->isValid()) {
                        $fail('Une erreur est survenue lors du téléchargement de l\'image.');
                    }
                },
            ]
         ]);
 
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
        if(Auth::check()){
            $request->merge(['user_id' => $user->id]);
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('images', 'public');
                $save = Ad::create([
                'images' => $imagePath,
                'title' => $request->title,
                'price' => $request->price,
                'country' => $request->country,
                'phone' => $request->phone,
                'price_type' => $request->price_type,
                'city' => $request->city,
                'delivery_status' => $request->delivery_status,
                'state' => $request->state,
            //  'visibility' => true,
                'description' => $request->description,
                'user_id' => $user->id,
                // 'subcategory_id' => $request->category_id 
                ]) ;
            }
            return response()->json($save, 201);
        }else{
            return response()->json("erreur");
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
