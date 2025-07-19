<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //create user
    public function create(Request $request)
    {

        try {
                $validator =  Validator::make($request->all(), [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255|unique:users',
                        'password' => 'required|string|min:8|confirmed',
                    ]);
                    if ($validator->fails()) {
                        return $this->sendError('Validation failed', $validator->errors(), 422);
                    
                    }
                    $request->merge(['password' => bcrypt($request->password)]); // Hash the password
                    
                    $user = User::create($request->all());
                    return $this->sendResponse( 'User created successfully',$user);
        } catch (\Exception $e) {
           return $this->sendError('Error creating user', ['error' => $e->getMessage()], 500);
        }

    }
 
    public function login(Request $request)
    {
       try{
            $credentials = $request->only('email', 'password');
            // validate the credentials
          $validator =  Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                $this->sendError('Validation failed', $validator->errors(), 422);
               // return response()->json(['message' => 'Validation failed', 'data'=>[], 'errors' => [$validator->errors()]], 422);
            }

            if (auth()->attempt($credentials)) {

                $token = auth()->user()->createToken(auth()->user()->name)->plainTextToken;
                return $this->sendResponse('User logged in successfully', ['token' => $token]);
            }
            return $this->sendError('Invalid credentials', [], 401);
        } catch (\Exception $e) {
            return $this->sendError('Error logging in', ['error' => $e->getMessage()], 500);
        }

    }
    //user logout
    public function logout(Request $request)
    {

       try {
            auth()->logout();
            return $this->sendResponse('User logged out successfully');

       } catch (\Exception $e) {
           return $this->sendError('Error logging out', ['error' => $e->getMessage()], 500);
       }


    }
    //list all bids history under the user
    public function userBidsHistory(Request $request,$userId){
        try {
            $validator = Validator::make(['userId' => $userId], [
                'userId' => 'required|integer|exists:users,id',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation failed', $validator->errors(), 422);
            }
        $user = User::findOrFail($userId);
        $bids = $user->bids()->with('slot')->get();
      //  $data = $user->only(['id', 'name', 'email'])->merge(['bids' => $bids])->toArray();

        return $this->sendResponse('Bids retrieved successfully', $bids);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving bids', ['error' => $e->getMessage()], 500);
        }
    }

}

