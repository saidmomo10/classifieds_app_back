<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'subscription_id',
        'fedapay_transaction_id',
        'amount',
        'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}