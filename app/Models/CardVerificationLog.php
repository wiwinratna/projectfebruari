<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardVerificationLog extends Model
{
    protected $fillable = [
        'card_id','qr_token','visitor_name','phone','note','ip_address','user_agent'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}