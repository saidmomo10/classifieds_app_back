<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\SubCategory;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class);
    }

    public function usersubscription(){
        return $this->belongsTo(UserSubscription::class);
    }

    public function subscription(){
        return $this->belongsTo(Subscription::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
