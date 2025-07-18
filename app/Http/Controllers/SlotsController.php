<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slots;

class SlotsController extends Controller
{
    //list all slots with pagination
    public function index(Request $request)
    {
        // Logic to retrieve and return all slots with pagination
        // This is where you would fetch the slots from the database and return them
        // Example:
        //apply filter status
        $status = $request->query('status');
        $query = Slots::query();
        if ($status) {
            $query->where('status', $status);
        }
        $slots = $query->paginate(10); // Adjust the number of items per page as needed
        return response()->json($slots);
    }
}
