<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bids;
use App\Models\Slots;

class BidsController extends Controller
{
    //add a bid
    public function create(Request $request)
    {
        $validated = $request->validate([
            'slot_id' => 'required|exists:slots,id',
            'amount' => 'required|numeric|min:0',
        ]);
        $slot = Slots::findOrFail($validated['slot_id'])->first();

       $ValidationMessage = $this->validateBiddingSlot($slot);

       if (trim($ValidationMessage)!='') 
        return response()->json(['message' => $ValidationMessage, 'data' => []], 400);

       $bid = Bids::create([
            'slot_id' => $validated['slot_id'],
            'user_id' => $request->user()->id,
            'amount' => $validated['amount'],
        ]);

        return response()->json(['message' => 'Bid created successfully','data'=>[$bid]], 201);
    }
    //list all slot bids for bidding
    public function index(Request $request, $slotId)
    {
        $slot = Slots::findOrFail($slotId)->first();
        $is_bidding = $request->has('is_bidding') && $request->query('is_bidding') == 'true' ? true : false;
        $ValidationMessage = $is_bidding ? $this->validateBiddingSlot($slot) : '';
       if (trim($ValidationMessage)!='') 
        return response()->json(['message' => $ValidationMessage, 'data' => []], 400);
        $bids = Bids::where('slot_id', $slotId)->with('user')->get();

        return response()->json(['message' => 'Bids retrieved successfully','data' => $bids]);
    }
    //validate slot
    public function validateBiddingSlot($slot)
    {
   
        $message = '';
        if ($slot->status === 'upcoming') {
            $message = 'Cannot bid on an upcoming slot';
        } elseif ($slot->status === 'closed') {
            $message = 'Slot is closed for bidding';
        } elseif ($slot->status === 'awarded') {
            $message = 'Slot is awarded for bidding';
        }

        return $message;
    }


}
