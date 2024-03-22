<?php

namespace App\Models;

use App\Models\Ad;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSubscription extends Model
{
    protected $table = 'user_subscriptions';
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subscription(){
        return $this->belongsTo(Subscription::class);
    }

    public function ads(){
        return $this->hasMany(Ad::class);
    }
}
