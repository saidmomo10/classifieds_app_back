<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdVideo extends Model
{
    use HasFactory;

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
