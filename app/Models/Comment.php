<?php

namespace App\Models;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ad(){
        return $this->belongsTo(Ad::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
