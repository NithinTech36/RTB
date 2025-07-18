<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slots extends Model
{
    use HasFactory;
    //table name
    protected $table = 'slots';
    //fillable attributes
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'status',
        'price',
    ];
    // Define relationships if needed
    // For example, if you want to define a relationship with Bids:
    public function bids()
    {
        return $this->hasMany(Bids::class, 'slot_id');
    }
}
