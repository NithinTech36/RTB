<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //create user
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create($request->all());

        return response()->json(['message' => 'User created successfully'], 201);
    }
 
    public function login(Request $request)
    {
// Logic to handle user login
        $credentials = $request->only('email', 'password');
        // validate the credentials
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($credentials)) {
            return response()->json(['message' => 'User logged in successfully']);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    //user logout
    public function logout(Request $request)
    {

        auth()->logout();
        return response()->json(['message' => 'User logged out successfully', 'data' => []]);
    }
    //list all bids history under the user
    public function userBidsHistory(Request $request,$userId){
        $user = User::findOrFail($userId);
        $bids = $user->bids()->with('slot')->get();
      //  $data = $user->only(['id', 'name', 'email'])->merge(['bids' => $bids])->toArray();

        return response()->json(['message' => 'Bids retrieved successfully', 'data' => $bids]);
    }

}

