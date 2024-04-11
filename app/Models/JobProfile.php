<?php

namespace App\Models;

use App\Models\User;
use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobProfile extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function jobcategory(){
        return $this->belongsTo(JobCategory::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
