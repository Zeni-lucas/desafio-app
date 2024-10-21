<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::orderby('created_at', 'desc')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {   
        
        $data = $request->validated();

        $birthday = Carbon::parse($data['birthday']);
        $age = $birthday->age;
        
        if ($age < 18) {
            return response()->json([
                'error' => "You must be at least 18 years old to create an account."
            ], 403);
        }
    
        
        $user = User::create($data);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {   
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->transactions()->exists() || $user->transactionsProducts()->exists()){
            return response()->json([
                'error' => "Cannot delete user with existing transactions or balance"
            ]);
        }

        $user->delete();

        return response()->json([
            'Message' => "User deleted Successfully!"
        ]);
    }
}
