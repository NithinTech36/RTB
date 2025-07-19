<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bids;
use App\Models\Slots;
use Illuminate\Support\Facades\Validator;

class BidsController extends Controller
{
    //add a bid
    public function create(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'slot_id' => 'required|exists:slots,id',
            'amount' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation failed', $validator->errors(), 422);
        }
        $slotId = $request->input('slot_id');
        $slot = Slots::findOrFail($slotId);


       $ValidationMessage = $this->validateBiddingSlot($slot);

       if (trim($ValidationMessage)!='') 
        return response()->json(['message' => $ValidationMessage, 'data' => []], 400);

       $bid = Bids::create([
            'slot_id' => $slotId,
            'user_id' => $request->user()->id,
            'amount' => $request->input('amount'),
        ]);

        return $this->sendResponse('Bid created successfully', $bid);
        } catch (\Exception $e) {
            return $this->sendError('Error creating bid', ['error' => $e->getMessage()], 500);
        }
    }
    //list all slot bids for bidding
    public function index(Request $request, $slotId)
    {
        try {
        $slot = Slots::findOrFail($slotId)->first();
        $is_bidding = $request->has('is_bidding') && $request->query('is_bidding') == 'true' ? true : false;
        $ValidationMessage = $is_bidding ? $this->validateBiddingSlot($slot) : '';
       if (trim($ValidationMessage)!='') 
        return $this->sendError('Validation failed', ['message' => $ValidationMessage], 400);
        $bids = Bids::where('slot_id', $slotId)->with('user')->get();

        return $this->sendResponse('Bids retrieved successfully', $bids);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving bids', ['error' => $e->getMessage()], 500);
        }
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
