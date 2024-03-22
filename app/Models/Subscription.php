<?php

namespace App\Models;

use App\Models\Ad;
use App\Models\File;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subscription extends Model
{
    use HasFactory;
    protected $guarded = [];

//     public function user()
// {
//     return $this->belongsTo(User::class);
// }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_subscriptions')->withPivot('activated_at');
    }

    public function ads(){
        return $this->hasManyThrough(
            Ad::class,
            UserSubscription::class,
            'subscription_id',
            'user_subscription_id',
            'id',
            'id'

        );
    }

    public function adss(){
        return $this->hasMany(
            Ad::class
            );
    }

    // public function files():BelongsToMany
    // {
    //     return $this->belongsToMany(File::class, 'subscription_file')->withPivot('subscription_id', 'file_id');
    // }

}