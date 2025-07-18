<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Slots;
use App\Models\Bids;
use App\Events\WinnerEvent;

class SetWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting the winner...');
        $winnerBids = [];
 
        $slots = Slots::where('status', '!=', 'awarded')->with('bids')->get();
        if ($slots->isEmpty()) {
            $this->info('No slots found to set winners.');
            return;
        }
        foreach ($slots as $slot) {

            $this->info("Processing slot ID: {$slot->id} with status: {$slot->status}");
            
            if ($slot->status === 'upcoming') {
                $currentTime = now();
                if ($currentTime->between($slot->start_time, $slot->end_time)) {
                    $slot->status = 'open';
                    $slot->save();
                    $this->info("Slot {$slot->id} status updated to opened.");
                }
            } elseif ($slot->status === 'open') {

                $currentTime = now();
                if ($currentTime > $slot->end_time) {
                    $slot->status = 'closed';
                    $slot->save();
                    $this->info("Slot {$slot->id} status updated to closed.");
                }
            } elseif ($slot->status === 'closed') {
                event(new WinnerEvent($slot->id));
              //   dispatch(new SetWinnerJob($slot->id))->afterCommit();
                /*
                $highestBid = $slot->bids()->orderBy('amount', 'desc')->first();
                $highestBidDatas = Bids::where('slot_id', $slot->id)
                   ->where('amount', $highestBid->amount)
                   ->orderBy('id', 'asc')
                   ->get();
                if($highestBidDatas->count() >1) $highestBid = $highestBidDatas->first();
                $slot->update(['status' => 'awarded']);
                  $this->info("Highest bid found for bid ID: {$highestBid->id} with amount: {$highestBid->amount}");

               */
                $this->info("Winner set for slot {$slot->id}.");
            }
        }


        $this->info('Winners have been set successfully.');

    }
}
