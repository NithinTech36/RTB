<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidWinner extends Model
{
    //
    protected $table = 'bid_winner';
    protected $fillable = ['bid_id', 'slot_id', 'user_id', 'amount']; // Assuming you want to store the winning bid amount
    // You can add any additional methods or relationships here if needed
    public function bid()
    {
        return $this->belongsTo(Bids::class, 'bid_id');
    }
    public function slot()
    {
        return $this->belongsTo(Slots::class, 'slot_id');
    }
}
