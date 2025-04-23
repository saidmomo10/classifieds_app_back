<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\JobProfile;
use App\Models\Subscription;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'ifu',
        'rccm',
        'website'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->generateSubscription();
        });
    }

    public function generateSubscription()
    {
        // $subscription = $this->subscriptions()->create([
        //     'name' => 'Free',
        //     'duration' => '0',
        //     'price' => '0',
        //     'description' => '0',
        //     'max_ads' => '1',
        //     'max_images' => '1',
        //     'type' => 'Gratuit',
        // ]);

        $subscription = Subscription::where('type', 'Gratuit')->first();


        $this->subscriptions()->attach($subscription->id, ['activated_at' => now(), 'status' => 'Abonnement actif', 'end_date' => now()->addMinutes($subscription->duration)]);
    }

    public function subscriptions():BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, 'user_subscriptions')
        ->withPivot('id','status','activated_at','end_date')
        ->withTimestamps();
    }

    public function getAvatarAttribute()
    {
        if ($this->attributes['avatar']) {
            return asset("storage/" . $this->attributes['avatar']); // Avatar stocké localement
        }

        $email = trim(strtolower($this->email));
        $hash = md5($email);
        return "https://www.gravatar.com/avatar/$hash?s=200&d=mp"; // Image Gravatar ou par défaut
    }

    public function ads(){
        return $this->hasMany(Ad::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function jobprofile(){
        return $this->hasOne(JobProfile::class);
    }
}