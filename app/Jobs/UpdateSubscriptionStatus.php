<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSubscriptionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        // Mettre à jour le statut des abonnements pour chaque utilisateur
        foreach ($users as $user) {
            // Appelle la logique que tu as dans SubscriptionController pour mettre à jour les abonnements
            // Exemple de mise à jour (tu devras adapter en fonction de ta logique)
            $subscription = $user->subscriptions()->latest('activated_at')->first();
            if ($subscription && $subscription->pivot->end_date > now()) {
                $subscription->pivot->status = 'Abonnement actif';
            } else {
                $subscription->pivot->status = 'Aucun abonnement';
            }
            $subscription->pivot->save();
        }
    }
}
