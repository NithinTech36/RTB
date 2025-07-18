<?php

namespace App\Jobs;

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
    
                   Log::info('Highest bid data count: ' . $highestBidDatas->count());
                  // Log:info('Highest bid data count: ' . $highestBidDatas->count());
                if($highestBidDatas->count() >1) $highestBid = $highestBidDatas->first();
                $savedSlot = Slots::find($this->slotId);
                $savedSlot->update(['status' => 'awarded']);
/*
               $slot = Slots::find($this->slotId)->load('bids');
               $highestBid = $slot->bids()->orderBy('amount', 'desc')->first();
               $highestBidCount = Bids::where('slot_id', $slot->id)
                   ->where('amount', $highestBid->amount)
                   ->count();

                   if($highestBidCount > 1) {
                       Log::info('Multiple highest bids found for slot ID: ' . $slot->id);
                       // Handle tie-breaking logic here if needed
                   } else {
                       Log::info('Single highest bid found for slot ID: ' . $slot->id);
                   }

               if ($highestBid) {
                   $slot->status = 'awarded';    


                 //  $slot->winner_id = $highestBid->user_id;
                   $slot->save();
               }
                   */

                   } catch (\Exception $e) {
            Log::error('Error occurred while setting winner: ' . $e->getMessage());
                   }


    }
}
