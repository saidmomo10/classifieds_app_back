<?php

namespace App\Http\Controllers\Api;


use App\Models\Ad;
use App\Models\File;
use App\Models\User;
use FedaPay\FedaPay;
use FedaPay\Customer;
use FedaPay\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\UserSubscription;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
        $subscriptions = Subscription::all();
        return response()->json($subscriptions);
    }

    public function getSubscriptionId(){
        $user = Auth::user();
    
        $subscription = $user->subscriptions()->latest('activated_at')->first();
    
        // Recherche de l'abonnement actif de l'utilisateur
        $key = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first();
    
        // Vérification de la validité de l'abonnement actif
        if($key != null && $key->pivot->end_date > now()){
            $key->pivot->status = 'Abonnement actif'; 
            $key->pivot->save();
        } else if($key != null && $key->pivot->end_date < now()) {
            // Si l'abonnement est expiré, détacher l'abonnement expiré
            $key->pivot->status = 'Aucun abonnement'; 
            $key->pivot->save();
        }
            return $key->pivot->id;
    }

    public function show()
    {
        $user = Auth::user();

        // Vérifier si l'utilisateur a un abonnement
        $subscription = $user->subscriptions()->latest('activated_at')->first();
        if (!$subscription) {
            return response()->json([
                'status' => 'Aucun abonnement',
                'subscription' => null
            ]);
        }

        // Recherche de l'abonnement actif lié à cet utilisateur
        $key = $user->subscriptions()
            ->where('subscription_id', $subscription->id)
            ->latest('activated_at')
            ->first();

        // Si aucun abonnement actif n'est trouvé
        if (!$key) {
            return response()->json([
                'status' => 'Aucun abonnement',
                'subscription' => null
            ]);
        }

        // Vérifier la validité de l'abonnement
        $isActive = $key->pivot->end_date > now();
        $key->pivot->status = $isActive ? 'Abonnement actif' : 'Aucun abonnement';
        $key->pivot->save();

        // Retourner les détails de l'abonnement
        return response()->json([
            'status' => $key->pivot->status,
            'subscription' => [
                'id' => $key->id,
                'name' => $key->name,
                'start_date' => $key->pivot->start_date,
                'end_date' => $key->pivot->end_date,
                'price' => $key->price ?? null,
                'type' => $key->type
            ]
        ]);
    }

    
    public function adStatus(){
        $user = Auth::user();
        $subscription = $user->subscriptions()->latest('activated_at')->first();

        $key = $user->subscriptions()->where('subscription_id', $subscription->id)->latest('activated_at')->first();
        // $subscription = $user->subscriptions()->where('status', 'Aucun abonnement')->latest('activated_at')->first();

        return response()->json($key);
    }

    public function createTransaction(Request $request, $id)
    {
        $user = Auth::user();
        $subscription = Subscription::findOrFail($id);

        try {
            // Configuration unique de FedaPay
            FedaPay::setApiKey(config('services.fedapay.secret_key'));
            FedaPay::setEnvironment(config('services.fedapay.env'));
            
            // // Création du client FedaPay
            // $customer = Customer::create([
            //     'firstname' => $user->name,
            //     'lastname' => '',
            //     'email' => $user->email,
            //     'phone' => [
            //         'number' => '22990000000',
            //         'country' => 'bj'
            //     ]
            // ]);
            
            // URL de callback via Ngrok
            // $ngrokUrl = 'https://85d0-137-255-30-114.ngrok-free.app'; // Remplacez par votre URL Ngrok actuelle
            // $callbackUrl = 'https://0295-137-255-30-114.ngrok-free.app/payment/success';
            
            // Création de la transaction avec callback
            $transaction = Transaction::create([
                'description' => 'Abonnement: ' . $subscription->name,
                'amount' => 1000,
                'currency' => ['iso' => 'XOF'],
                'customer' => [
                    'name' => $user->name,
                ],
                'callback_url' => config('services.fedapay.callback_url'),
                // 'return_url' => 'http://localhost:5173/payment/success',
                'metadata' => [
                    'user_id' => $user->id,
                    'subscription_id' => $subscription->id
                ]
            ]);
            
            // Enregistrer la transaction en attente dans la base de données
            $paymentTransaction = new PaymentTransaction();
            $paymentTransaction->user_id = $user->id;
            $paymentTransaction->subscription_id = $subscription->id;
            $paymentTransaction->fedapay_transaction_id = $transaction->id;
            $paymentTransaction->amount = $subscription->price ?? 1000;
            $paymentTransaction->status = 'pending';
            $paymentTransaction->save();
            
            // Générer l'URL de paiement
            $paymentData = $transaction->generateToken();
            
            // // Pour le développement local uniquement
            // if (app()->environment('local') && env('FEDAPAY_AUTO_ACTIVATE', false)) {
            //     Log::warning('DEV MODE: Activation automatique de l\'abonnement sans vérification de paiement');
                
            //     // Cette partie n'est que pour le développement rapide, à supprimer en production
            //     $this->activateSubscription($user, $subscription);
            // }
            
            return response()->json([
                'message' => 'Transaction créée, veuillez procéder au paiement',
                'transaction_id' => $transaction->id,
                'payment_url' => $paymentData->url
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Erreur FedaPay: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création de la transaction: ' . $e->getMessage()], 500);
        }
    }

    // Méthode pour activer l'abonnement
    private function activateSubscription($user, $subscription)
    {
        // Activer le nouvel abonnement
        $user->subscriptions()->attach($subscription->id, [
            'activated_at' => now(),
            'status' => 'Abonnement actif',
            'end_date' => now()->addMinutes($subscription->duration)
        ]);
        
        // Désactiver l'ancien abonnement s'il existe
        $previousSubscription = $user->subscriptions()
                                    ->where('subscription_id', '!=', $subscription->id)
                                    ->where('pivot.status', 'Abonnement actif')
                                    ->first();
        if ($previousSubscription) {
            $user->subscriptions()->updateExistingPivot($previousSubscription->id, [
                'status' => 'Aucun abonnement'
            ]);
        }
    }

    // Gestionnaire de callback
    public function handlePaymentCallback(Request $request){
        try {

            // if ($request->method() === 'GET') {
            //     // Simple réponse pour les tests ou redirections GET
            //     return response()->json(['status' => 'Callback GET reçu mais ignoré'], 200);
            // }
            
            FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY', 'sk_sandbox_Gumwsrfd-oSl8q4z2xgY90M8'));
            FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox'));
            

            $data = $request->all();
            

            if (isset($data['entity']['id'])) {
                $transactionId = $data['entity']['id'];
                
                Log::info('Données reçues dans callback FedaPay', $data);

                $fedaTransaction = Transaction::retrieve($transactionId);
                $paymentTransaction = PaymentTransaction::where('fedapay_transaction_id', $transactionId)->first();
                Log::info('Statut FedaPay reçu : ' . $fedaTransaction->status);
                if ($paymentTransaction && $fedaTransaction->status === 'approved') {
                    Log::info('Statut FedaPay reçu : ' . $fedaTransaction->status);

                    $user = User::find($paymentTransaction->user_id);
                    $subscription = Subscription::find($paymentTransaction->subscription_id);

                    

                    if ($user && $subscription) {
                        // Activation immédiate de l'abonnement **sans vérifier le paiement**
                        $user->subscriptions()->attach($subscription->id, [
                            'activated_at' => now(),
                            'status' => 'Abonnement actif',
                            'end_date' => now()->addMinutes($subscription->duration)
                        ]);

                        // Désactiver l'ancien abonnement s'il y en a un
                        $previousSubscription = $user->subscriptions()
                                                    ->latest('activated_at')
                                                    ->skip(1) // Ignorer le dernier abonnement
                                                    ->first();
                        if ($previousSubscription) {
                            $previousSubscription->pivot->status = 'Aucun abonnement';
                            $previousSubscription->pivot->save();
                        }

                        Log::info('Paiement confirmé et abonnement activé pour l\'utilisateur: ' . $user->id);
                    }
                }
            }

            // return response()->json(['message' => 'Callback traité avec succès'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur callback FedaPay: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    // public function createTransaction(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $subscription = Subscription::findOrFail($id);

    //     try {
    //         FedaPay::setApiKey("sk_sandbox_Gumwsrfd-oSl8q4z2xgY90M8");
    //         FedaPay::setEnvironment('sandbox');

    //         $customer = \FedaPay\Customer::create([
    //             'firstname' => $user->name,
    //             'lastname' => '',
    //             'phone' => [
    //                 'number' => '22990000000',
    //                 'country' => 'bj'
    //             ]
    //         ]);

    //         $transaction = Transaction::create([
    //             'description' => 'Payment for subscription',
    //             'amount' => '1000', 
    //             'currency' => ['iso' => 'XOF'],
    //             'callback_url' => url('/api/fedapay/callback') // Callback avec ngrok
    //         ]);

    //         return response()->json([
    //             'message' => 'Transaction créée avec succès!',
    //             'transaction_id' => $transaction->id,
    //             'payment_url' => $transaction->generateToken()->url
    //         ], 201);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Erreur lors de la création de la transaction'], 500);
    //     }
    // }

    // public function handleCallback(Request $request)
    // {
    //     Log::info('Callback reçu de FedaPay', $request->all());

    //     try {
    //         $transactionId = $request->input('transaction_id');
    //         $subscriptionId = $request->input('subscription_id');

    //         // Vérifie si l'ID de la transaction est présent
    //         if (!$transactionId) {
    //             Log::error('Aucun ID de transaction reçu');
    //             return response()->json(['error' => 'ID de transaction manquant'], 400);
    //         }

    //         // Vérifier que l'ID de l'abonnement est bien présent
    //         if (!$subscriptionId) {
    //             Log::error('Aucun ID d\'abonnement reçu');
    //             return response()->json(['error' => 'ID d\'abonnement manquant'], 400);
    //         }

    //         // Récupérer la transaction depuis FedaPay
    //         FedaPay::setApiKey("sk_sandbox_Gumwsrfd-oSl8q4z2xgY90M8");
    //         FedaPay::setEnvironment('sandbox');

    //         $transaction = Transaction::retrieve($transactionId);
    //         Log::info('Transaction récupérée', ['transaction' => $transaction]);

    //         // Vérifier si le paiement a été approuvé
    //         if ($transaction->status !== 'approved') {
    //             Log::error('Paiement non approuvé', ['status' => $transaction->status]);
    //             return response()->json(['error' => 'Paiement non approuvé'], 400);
    //         }

    //         // Trouver l'utilisateur par email
    //         $user = User::where('email', $transaction->customer->email)->first();
    //         if (!$user) {
    //             Log::error('Utilisateur non trouvé', ['email' => $transaction->customer->email]);
    //             return response()->json(['error' => 'Utilisateur non trouvé'], 404);
    //         }

    //         // Récupérer l'abonnement à activer
    //         $subscription = Subscription::findOrFail($subscriptionId);

    //         // Activer l'abonnement pour l'utilisateur
    //         $user->subscriptions()->attach($subscription->id, [
    //             'activated_at' => now(),
    //             'status' => 'Abonnement actif',
    //             'end_date' => now()->addMinutes($subscription->duration)
    //         ]);
            
    //         // Désactiver l'ancien abonnement si nécessaire
    //         $previousSubscription = $user->subscriptions()
    //                                         ->latest('activated_at')
    //                                         ->skip(1) // Ignorer le dernier abonnement
    //                                         ->first();
    //         if ($previousSubscription) {
    //             $previousSubscription->pivot->status = 'Aucun abonnement';
    //             $previousSubscription->pivot->save();
    //         }

    //         Log::info('Abonnement activé pour l\'utilisateur', ['user_id' => $user->id, 'subscription_id' => $subscription->id]);

    //         return response()->json(['message' => 'Paiement validé et abonnement activé']);
    //     } catch (\Exception $e) {
    //         Log::error('Erreur lors de la vérification du paiement', ['error' => $e->getMessage()]);
    //         return response()->json(['error' => 'Erreur lors de la vérification du paiement'], 500);
    //     }
    // }


    


    public function affect($id){
        $subscription = Subscription::findOrFail($id);
        // $file = File::findOrfail($id);
        $fileIds = request('file_ids');

        // $file->subscriptions()->attach($subscription->id);

        $subscription->files()->attach($fileIds);
        
        return back()->with('success', 'Chaine affecté avec succès!');
    }
}




// // public function createTransaction(Request $request, $id)
//     {
//         $user = Auth::user();
//         $subscription = Subscription::findOrFail($id); // Vérifie que l'abonnement existe.

//         // Activation immédiate de l'abonnement **sans vérifier le paiement**
//         $user->subscriptions()->attach($subscription->id, [
//             'activated_at' => now(),
//             'status' => 'Abonnement actif',
//             'end_date' => now()->addMinutes($subscription->duration)
//         ]);

//         // Désactiver l'ancien abonnement s'il y en a un
//         $previousSubscription = $user->subscriptions()
//                                     ->latest('activated_at')
//                                     ->skip(1) // Ignorer le dernier abonnement
//                                     ->first();
//         if ($previousSubscription) {
//             $previousSubscription->pivot->status = 'Aucun abonnement';
//             $previousSubscription->pivot->save();
//         }

//         return response()->json([
//             'message' => 'Abonnement activé avec succès!',
//             // 'transaction_id' => $transaction->id,
//             // 'payment_url' => $transaction->generateToken()->url
//         ], 201);

//         // // Création du client FedaPay
//         // try {
//         //     FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY', 'sk_sandbox_Gumwsrfd-oSl8q4z2xgY90M8'));
//         //     FedaPay::setEnvironment(env('FEDAPAY_ENV', 'sandbox'));
            
//         //     $customer = Customer::create([
//         //         'firstname' => $user->name,
//         //         'lastname' => '',
//         //         'phone' => [
//         //             'number' => '22990000000', // Remplace par le vrai numéro de l'utilisateur
//         //             'country' => 'bj'
//         //         ]
//         //     ]);
//         // } catch (\Exception $e) {
//         //     return response()->json(['error' => 'Erreur lors de la création du client FedaPay'], 500);
//         // }

//         // // Création de la transaction
//         // try {
//         //     FedaPay::setApiKey("sk_sandbox_Gumwsrfd-oSl8q4z2xgY90M8");
//         //     FedaPay::setEnvironment('sandbox');
            
//         //     $transaction = Transaction::create([
//         //         'description' => 'Payment for subscription',
//         //         'amount' => '1000', // Utilisation du prix de l'abonnement
//         //         'currency' => ['iso' => 'XOF'],
//         //         // 'callback_url' => 'https://example.com/payment/callback' // URL non nécessaire ici
//         //     ]);

//         //     // Activation immédiate de l'abonnement **sans vérifier le paiement**
//         //     $user->subscriptions()->attach($subscription->id, [
//         //         'activated_at' => now(),
//         //         'status' => 'Abonnement actif',
//         //         'end_date' => now()->addMinutes($subscription->duration)
//         //     ]);

//         //     // Désactiver l'ancien abonnement s'il y en a un
//         //     $previousSubscription = $user->subscriptions()
//         //                                 ->latest('activated_at')
//         //                                 ->skip(1) // Ignorer le dernier abonnement
//         //                                 ->first();
//         //     if ($previousSubscription) {
//         //         $previousSubscription->pivot->status = 'Aucun abonnement';
//         //         $previousSubscription->pivot->save();
//         //     }

//         //     return response()->json([
//         //         'message' => 'Abonnement activé avec succès!',
//         //         'transaction_id' => $transaction->id,
//         //         'payment_url' => $transaction->generateToken()->url
//         //     ], 201);
            
//         // } catch (\Exception $e) {
//         //     return response()->json(['error' => 'Erreur lors de la création de la transaction'], 500);
//         // }
//     }