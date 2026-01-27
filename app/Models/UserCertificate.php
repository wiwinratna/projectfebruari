<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    protected $fillable = [
            'user_id',
            'file_path',
            'original_name',
            'title',
            'event_date',
            'stage',
            'event_date' => 'date',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
