<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
