<?php

namespace App\Models;

use App\Models\File;
use App\Models\User;
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
        return $this->belongsToMany(User::class, 'user_subscription')->withPivot('activated_at');
    }

    // public function files():BelongsToMany
    // {
    //     return $this->belongsToMany(File::class, 'subscription_file')->withPivot('subscription_id', 'file_id');
    // }

}