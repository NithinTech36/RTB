<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Bids extends Model
{
    use HasFactory;
    protected $table = 'bids';
    // Fillable attributes
    protected $fillable = [
        'slot_id',
        'user_id',
        'amount',
    ];
    // Define relationships if needed
    // For example, if you want to define a relationship with Slots and Users:  
    public function slot()
    {
        return $this->belongsTo(Slots::class, 'slot_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
