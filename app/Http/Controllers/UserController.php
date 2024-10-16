<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::latest()->get();
        return $users;
    }


    public function store(Request $request) {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
    }

    public function update(Request $request, User $user){
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email, '. $user->id,
            'password' => 'sometimes|min:8'
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->has('password') ? bcrypt($request->password) : $user->password
        ]);

        return $user;
    }
}
