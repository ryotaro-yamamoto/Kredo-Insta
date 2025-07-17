<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function show($id){
        // Fetch the user by ID
        $user = $this->user->findOrFail($id);

        return view('users.profile.show')->with('user', $user);
    }

    public function edit(){
        $user = $this->user->findOrFail(Auth::user()->id);

        return view('users.profile.edit')->with('user', $user);
    }

    //New(RIKO)//
    public function update(Request $request,$id){
        $user = User::findOrFail($id);
        // Validate the request
        $request->validate([
            'avatar' => 'mimes:jpeg,png,jpg,gif|max:1048',
            'name' => 'required|min:1|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->id,
            'introduction' => 'max:500',

            // change password
            'current_password' => ['nullable', 'current_password'], 
            'new_password' => ['nullable', 'min:8', 'confirmed']
        ]);

        // Update user information
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->introduction = $request->introduction;

        if ($request->avatar) {
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        $user->save();

        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($id){
        $user = $this->user->findOrFail($id);

        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id){
        $user = $this->user->findOrFail($id);

        return view('users.profile.following')->with('user', $user);
    }


}