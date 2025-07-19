<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slots;
use Illuminate\Support\Facades\Validator;

class SlotsController extends Controller
{
    //list all slots with pagination
    public function index(Request $request)
    {
 
        try {
            $status = $request->query('status');
            $query = Slots::query();
            if ($status) {
                $query->where('status', $status);
            }
            $slots = $query->paginate(10); // Adjust the number of items per page as needed
            return $this->sendResponse('Slots retrieved successfully', $slots);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving slots', ['error' => $e->getMessage()], 500);
        }

    }

        public function viewWinningBid($slotId)
    {
        try {
            $slot = Slots::findOrFail($slotId);
            if ($slot->status !== 'awarded') {
                return $this->sendError('Slot is not awarded yet', [], 400);
            }

            $winningBid = $slot->bidWinners()->with('bid.user')->first();
            if (!$winningBid) {
                return $this->sendError('No winning bid found', [], 404);
            }

            return $this->sendResponse('Winning bid retrieved successfully', $winningBid);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving winning bid', ['error' => $e->getMessage()], 500);
        }

    }
}
