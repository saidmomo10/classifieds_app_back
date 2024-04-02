<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;

use App\Models\File;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubscriptionController extends Controller
{
    public function addSubscriptions()
    {
        return view('subscriptionsViews.addSubscriptions');
    }

    public function createSubscription(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'max_ads' => 'required',
            'type' => 'required',
            'max_images' => 'required'
        ]);

        // Créer un nouvel abonnement
        $subscription = new Subscription([
            // 'user_id' => auth()->user()->id,
            // 'start_date' => now(),
            'name' => $request->input('name'),
            'duration' => $request->input('duration'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'max_ads' => $request->input('max_ads'),
            'max_images' => $request->input('max_images'),


        ]);
        // $subscription->update(['start_date'=> now()]);
        $subscription->save();

        return response()->json('succes', 201);
    }

    public function list()
    {
        return Subscription::all();
    }

    public function getSubscriptionId(){
        $user = Auth::user();
        // $currentMonthAdsCount = $user->ads()->count();
    
        // Recherche de l'abonnement payant actif
        $subscription = $user->subscriptions()->latest('activated_at')->first();
    
        // Recherche de l'abonnement actif de l'utilisateur
        $key = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first();
    
        // Vérification de la validité de l'abonnement actif
        if($key != null && $key->pivot->end_date > now()){
            $key->pivot->status = 'Abonnement actif'; 
            $key->pivot->save();
        } else if($key != null && $key->pivot->end_date < now()) {
            // Si l'abonnement est expiré, détacher l'abonnement expiré
            $key->pivot->status = 'Abonnement expire'; 
            $key->pivot->save();
            // if ($subscription != null) {
            //     $user->subscriptions()->detach($subscription->id);
            // }
        }
    
        // return $key->pivot->id;
        return $key->pivot->id;
    }

    public function show(){
        $user = Auth::user();
        // $currentMonthAdsCount = $user->ads()->count();
    
        // Recherche de l'abonnement payant actif
        $subscription = $user->subscriptions()->latest('activated_at')->first();
    
        // Recherche de l'abonnement actif de l'utilisateur
        $key = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first();
    
        // Vérification de la validité de l'abonnement actif
        if($key != null && $key->pivot->end_date > now()){
            $key->pivot->status = 'Abonnement actif'; 
            $key->pivot->save();
        } else if($key != null && $key->pivot->end_date < now()) {
            // Si l'abonnement est expiré, détacher l'abonnement expiré
            $key->pivot->status = 'Abonnement expire'; 
            $key->pivot->save();
            // if ($subscription != null) {
            //     $user->subscriptions()->detach($subscription->id);
            // }
        }
    
        // return $key->pivot->id;
        return $key->pivot;
    }
    
    public function adStatus(){
        $user = Auth::user();
        $subscription = $user->subscriptions()->latest('activated_at')->first();

        $key = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first();
        // $subscription = $user->subscriptions()->where('status', 'Abonnement expire')->latest('activated_at')->first();

        return response()->json($key);
    }
    
    
    





    // $user = Auth::user();
    // // $currentMonthAdsCount = $user->ads()->count();

    // // Recherche de l'abonnement payant actif
    // $subscription = $user->subscriptions()->where('status', 'Abonnement actif')->latest('activated_at')->first();
    // $key = $user->subscriptions()->where('status', 'Abonnement expire')->latest('activated_at')->first();

    // // // Recherche de l'abonnement actif de l'utilisateur
    // // $key = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first();

    // // // Vérification de la validité de l'abonnement actif
    // // if($key != null && $key->pivot->end_date > now()){
    // //     $key->pivot->status = 'Abonnement actif'; 
    // //     $key->pivot->save();
    // // } else if($key != null && $key->pivot->end_date < now()) {
    // //     // Si l'abonnement est expiré, détacher l'abonnement expiré
    // //     $key->pivot->status = 'Abonnement expire'; 
    // //     $key->pivot->save();
    // //     // if ($subscription != null) {
    // //     //     $user->subscriptions()->detach($subscription->id);
    // //     // }
    // // }

    // // // return $key->pivot->id;
    // return response()->json([
    //     'key' => $key,
    //     'subscription' => $subscription
    // ]);












    public function activate($id)
    {
        $user = auth()->user();
        // dd($user);
    //     if (!$user) {
    //     return back()->with('message', 'Utilisateur non trouvé.');
    // }
        $subscription = Subscription::findOrFail($id);

        // Vérifier si l'abonnement est déjà activé
        // if ($subscription->end_date >= now()) {
        //     return back()->with('message', 'Cet abonnement est déjà actif.');
        // }

        // if($user->subscriptions()->where('subscription_id', $subscription->id)->exists()){
        //     return back()->with('message', 'Cet abonnement est déjà actif.');
        // }

        // $subscription->status = 'Abonnement actif';

        // Activer l'abonnement en mettant à jour les dates
        // $subscription->user_id = auth()->user()->id;
        $user->subscriptions()->attach($subscription->id,['activated_at' => now(), 'status' => 'Abonnement actif', 'end_date' => now()->addMinutes($subscription->duration)]);
        // $subscription->start_date = now();
        // $subscription->end_date = now()->addMinutes($subscription->duration); // ou utilisez la logique appropriée
        $subscription->save();
        // $this->status($subscriptions);
        $key = $user->subscriptions()
                     ->latest('activated_at')
                     ->skip(1) // Ignorer le dernier abonnement
                     ->first();
        $key->pivot->status = 'Abonnement expire';
        $key->pivot->save();

        return response()->json('Abonnement activé avec succès!', 201);
        

        // return back()->with('success', 'Abonnement activé avec succès!');
    }








    





    // public function activate($id)
    // {
    //     $user = auth()->user();
    //     // dd($user);
    // //     if (!$user) {
    // //     return back()->with('message', 'Utilisateur non trouvé.');
    // // }
    //     $subscription = Subscription::findOrFail($id);

    //     // Vérifier si l'abonnement est déjà activé
    //     // if ($subscription->end_date >= now()) {
    //     //     return back()->with('message', 'Cet abonnement est déjà actif.');
    //     // }

    //     // if($user->subscriptions()->where('subscription_id', $subscription->id)->exists()){
    //     //     return back()->with('message', 'Cet abonnement est déjà actif.');
    //     // }

    //     // $subscription->status = 'Abonnement actif';

    //     // Activer l'abonnement en mettant à jour les dates
    //     // $subscription->user_id = auth()->user()->id;

    //     $key = $user->subscriptions()->latest('activated_at')->first();


    //     if ($subscription->type === 'Payant') {
    //         // Pour les abonnements gratuits, définissez la date de fin à un mois à partir de maintenant
    //         // $subscription->end_date = now()->addMonth();
    //         $user->subscriptions()->attach($subscription->id,['activated_at' => now(), 'status' => 'Abonnement actif', 'end_date' => now()->addMinutes($subscription->duration)]);

    //     } else {
    //         // Pour les abonnements payants, laissez la date de fin null
    //         $user->subscriptions()->attach($subscription->id,['activated_at' => now(), 'status' => 'Mode gratuit', 'end_date' => null]);
    //     }



    //     // $user->subscriptions()->attach($subscription->id,['activated_at' => now(), 'status' => 'Abonnement actif', 'end_date' => now()->addMinutes($subscription->duration)]);
    //     // $subscription->start_date = now();
    //     // $subscription->end_date = now()->addMinutes($subscription->duration); // ou utilisez la logique appropriée
    //     $subscription->save();
    //     // $this->status($subscriptions);
        
    //     $pivotId = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first()->pivot;

    // // Si vous souhaitez retourner l'ID de la table pivot dans la réponse JSON
    // return response()->json(['pivot_id' => $pivotId, 'subscription' => $subscription], 201);
    //     // return back()->with('success', 'Abonnement activé avec succès!');
    // }

    // // public function status($id)
    // // {
    // //     $subscriptions = Subscription::findOrFail($id);

    // //     // Vérifier si l'abonnement est déjà activé
    // //     if ($subscriptions->end_date <= now()) {
    // //         $subscriptions->status = 'Abonnement non actif'; 
    // //         $subscriptions->save();
    // //     }else{
    // //         $subscriptions->status = 'Abonnement actif'; 
    // //         $subscriptions->save();
    // //     }
        
    // //     $subscriptions->save();
    // //     dd($subscriptions);
    // //     return back();
    // // }







        public function affect($id){
            $subscription = Subscription::findOrFail($id);
            // $file = File::findOrfail($id);
            $fileIds = request('file_ids');

            // $file->subscriptions()->attach($subscription->id);

            $subscription->files()->attach($fileIds);
        //     if($subscription->files()->where('file_id', $file->id)->exists()){
        //     return back()->with('message', 'Cet abonnement est déjà actif.');
        // }
            
            // $subscription->save();
            
            return back()->with('success', 'Chaine affecté avec succès!');
        }
}