<?php

namespace App\Jobs;

use App\Models\BidWinner;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Slots;
use App\Models\Bids;

use Illuminate\Support\Facades\Log;

class SetWinnerJob implements ShouldQueue
{
       use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance for setting the winner of a slot.
     */
    protected $slotId;
    public function __construct($slotId)
    {
        $this->slotId = $slotId;
    }

    /**
     * Execute the job.
     */ 
    public function handle(): void
    {
        try {
            $highestBid = Bids::where('slot_id', $this->slotId)->orderBy('amount', 'desc')->first();
            $highestBidDatas = Bids::where('slot_id', $this->slotId)
                ->where('amount', $highestBid->amount)
                ->orderBy('id', 'asc')
                ->get();

                if($highestBidDatas->count() >1) $highestBid = $highestBidDatas->first();
                $savedSlot = Slots::find($this->slotId);
           
                $savedSlot->update(['status' => 'awarded']);
                     BidWinner::create([
                    'bid_id' => $highestBid->id,
                    'slot_id' => $highestBid->slot_id,
                    'user_id' => $highestBid->user_id,
                    'amount' => $highestBid->amount,
                ]);


                   } catch (\Exception $e) {
            Log::error('Error occurred while setting winner: ' . $e->getMessage());
                   }


    }
}
